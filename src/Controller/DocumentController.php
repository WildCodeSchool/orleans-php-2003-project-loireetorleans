<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Document;
use App\Entity\Message;
use App\Form\DocumentType;
use App\Form\MessageType;
use App\Form\SearchingType;
use App\Repository\ConversationRepository;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use App\service\ConversationManager;
use DateTime;
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

    /**
     * @Route("/", name="document_index", methods={"GET"})
     * @param DocumentRepository $documentRepository
     * @return Response
     * @param Request $request
     * @IsGranted("ROLE_AMBASSADEUR")
     */

    public function index(DocumentRepository $documentRepository, Request $request): Response
    {
        $form = $this->createForm(SearchingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $documentRepository->findBySearch($data['search']);
        } else {
            $documentRepository->documentByDate();
        }


        return $this->render('document/index.html.twig', [
            'form' => $form->createView(),
            'documents' => $documentRepository,
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
        $form = $this->createForm(DocumentType::class, $document);
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

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            $users = $userRepository->findAllWhithoutAdmin();

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
     * @param UserInterface $user
     * @param UserRepository $userRepository
     * @param ConversationManager $conversationManager
     * @return Response
     * @IsGranted("ROLE_AMBASSADEUR")
     */
    public function show(
        Document $document,
        ConversationRepository $conversationRepo,
        Request $request,
        UserInterface $user,
        UserRepository $userRepository,
        ConversationManager $conversationManager
    ): Response {
        $message = new Message();
        $login = $user->getUsername();
        $persons = $userRepository->findTwoForMessage($login);
        $author = $userRepository->findOneBy(['login' => $login]);
        $conversation = $conversationRepo->findOneByConversation($document->getId(), $author);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($conversationManager->conversationExist($document->getId(), $user->getSalt()) === false) {
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

            return $this->redirectToRoute('document_show', [
                'id' => $document->getId(),
            ]);
        }

        return $this->render('document/show.html.twig', [
            'document' => $document,
            'conversation' => $conversation,
            'messageForm' => $form->createView(),
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
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

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

        return $this->redirectToRoute('document_index');
    }
}
