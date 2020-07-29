<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Document;
use App\Entity\Message;
use App\Entity\User;
use App\Form\DocumentType;
use App\Form\MessageType;
use App\Form\SearchingType;
use App\Repository\ConversationRepository;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use App\service\ConversationManager;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    const DOCS_PER_PAGE = 8;

    /**
     * @Route("/", name="document_index", methods={"GET"})
     * @param DocumentRepository $documentRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @IsGranted("ROLE_AMBASSADEUR")
     * @return Response
     */
    public function index(
        DocumentRepository $documentRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $form = $this->createForm(SearchingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $documents = $documentRepository->findBySearch($data['search']);
        } else {
            $documents = $documentRepository->documentByDate();
        }

        $docs = $paginator->paginate(
            $documents,
            $request->query->getInt('page', 1),
            self::DOCS_PER_PAGE,
            ['pageOutOfRange' => 'fix']
        );

        return $this->render('document/index.html.twig', [
            'form' => $form->createView(),
            'documents' => $docs,
        ]);
    }

    /**
     * @Route("/ajout", name="document_new", methods={"GET","POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     * @return Response
     * @throws TransportExceptionInterface
     * @IsGranted("ROLE_ADMINISTRATEUR")
     */
    public function new(Request $request, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document, [
            'validation_groups' => ['add','default'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $extension = $data->getDocumentFile()->getClientOriginalName();
            $extension = pathinfo($extension, PATHINFO_EXTENSION);
            if ($extension === 'docx' || $extension === 'doc') {
                $document->setExt('word');
            } else {
                $document->setExt($extension);
            }
            $document->setDocument($extension);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            $users = $userRepository->findAllValidateWhithoutAdmin();

            $email = (new Email())
                ->from('noreply@loireetorleans.fr');
            foreach ($users as $user) {
                $email = $email->addBcc($user->getEmail());
            }
            $email = $email->subject('Nouveau Document Disponible | Loire&Orleans')
                ->html($this->renderView('notification/newDocument.html.twig', [
                    'document' => $document,
                ]), 'utf8');

            $mailer->send($email);
            $this->addFlash(
                'success',
                'Le document a bien été ajouté !'
            );
            return $this->redirectToRoute('document_index');
        }


        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'registrationForm' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}", name="document_show", methods={"GET", "POST"})
     * @param Document $document
     * @param ConversationRepository $conversationRepo
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ConversationManager $conversationManager
     * @return Response
     * @IsGranted("ROLE_AMBASSADEUR")
     */
    public function show(
        Document $document,
        ConversationRepository $conversationRepo,
        Request $request,
        UserRepository $userRepository,
        ConversationManager $conversationManager
    ): Response {
        $message = new Message();
        /**
         * @var User
         */
        $user = $this->getUser();
        $login = $user->getUsername();
        $persons = $userRepository->findTwoForMessage($login);
        $author = $userRepository->findOneBy(['login' => $login]);
        $conversation = $conversationRepo->findOneByConversation($document->getId(), $author);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if (in_array('ROLE_ADMINISTRATEUR', $user->getRoles())) {
            $conversations = $conversationRepo->findBy(['document' => $document]);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($conversationManager->conversationExist($document->getId(), $user) === false) {
                $conversation = new Conversation();
                $conversation->setDocument($document);
                foreach ($persons as $person) {
                    $conversation->addUser($person);
                    if ($person->getLogin() === $login) {
                        $data->setUser($person);
                    }
                }
            } else {
                $data->setUser($author);
            }
            $data->setDate(new dateTime());
            $data->setConversation($conversation);
            $conversation->addMessage($data);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->persist($conversation);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé !'
            );
            return $this->redirectToRoute('document_show', [
                'id' => $document->getId(),
            ]);
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'conversation' => $conversation,
            'messageForm' => $form->createView(),
            'conversations' => $conversations ?? []
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Document $document
     * @return Response
     * @IsGranted("ROLE_ADMINISTRATEUR")
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document, [
            'validation_groups' => ['default'],
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data->getDocumentFile() !== null) {
                $extension = $data->getDocumentFile()->getClientOriginalName();
                $extension = pathinfo($extension, PATHINFO_EXTENSION);
                if ($extension === 'docx' || $extension === 'doc') {
                    $document->setExt('word');
                } else {
                    $document->setExt($extension);
                }
            }

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                'Le document a bien été mis à jour !'
            );
            return $this->redirectToRoute('document_index');
        }


        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_delete", methods={"DELETE"})
     * @param Request $request
     * @param Document $document
     * @return Response
     * @IsGranted("ROLE_ADMINISTRATEUR")
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }
        $this->addFlash(
            'success',
            'Le document a bien été supprimé !'
        );
        return $this->redirectToRoute('document_index');
    }
}
