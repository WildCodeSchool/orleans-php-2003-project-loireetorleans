<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Document;
use App\Entity\Message;
use App\Entity\User;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\DocumentRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\service\ConversationManager;
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
     * @param ConversationRepository $conversationRepo
     * @return Response
     * @IsGranted("ROLE_AMBASSADEUR")
     */
    public function index(ConversationRepository $conversationRepo): Response
    {
        return $this->render('message/index.html.twig', [
            'conversations'=> $conversationRepo->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="message_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_AMBASSADEUR")
     * @param Request $request
     * @param Message $message
     * @return Response
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
     * @IsGranted("ROLE_AMBASSADEUR")
     * @param Request $request
     * @param Message $message
     * @return Response
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
