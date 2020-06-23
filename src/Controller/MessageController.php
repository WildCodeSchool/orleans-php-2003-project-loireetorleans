<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Document;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\DocumentRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/message", name="message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(MessageRepository $messageRepository): Response
    {
        return $this->render('message/index.html.twig', [
            'messages' => $messageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{document}/nouveau", name="_new", methods={"GET","POST"})
     * @IsGranted("ROLE_AMBASSADEUR")
     */
    public function new(Request $request, Document $document, UserInterface $user, UserRepository $users): Response
    {
        $message = new Message();
        $users = $users->findTwoForMessage($user->getUsername());
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation = new Conversation();
            $conversation->setDocument($document);
            $conversation->addMessage($message);
            foreach ($users as $user) {
                $conversation->addUser($user);
            }

            $data = $form->getData();
            $data->setDate(new dateTime());
            $data->setConversation($conversation);
            $entityManager = $this->getDoctrine()->getManager();
            $data->setAuthor($this->getUser());
            $entityManager->persist($data);
            $entityManager->persist($conversation);
            $entityManager->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/new.html.twig', [
            'message' => $message,
            'messageForm' => $form->createView(),
            'document' => $document,
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}", name="message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Message $message): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('message_index');
        }

        return $this->render('message/edit.html.twig', [
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="message_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Message $message): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('message_index');
    }
}
