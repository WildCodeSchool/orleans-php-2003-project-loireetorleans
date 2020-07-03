<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="document_index", methods={"GET"})
     * @param DocumentRepository $documentRepository
     * @return Response
     */
    public function index(DocumentRepository $documentRepository): Response
    {

        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->documentByDate(),
        ]);
    }

    /**
     * @Route("/ajout", name="document_new", methods={"GET","POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @param UserRepository $userRepository
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request, MailerInterface $mailer, UserRepository $userRepository): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
     * @Route("/{id}", name="document_show", methods={"GET"})
     * @param Document $document
     * @return Response
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Document $document
     * @return Response
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
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_index');
    }
}
