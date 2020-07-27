<?php

namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\ConversationRepository;
use App\Repository\UserRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/admin/conversation", name="admin_conversation")
 */
class AdminConversationController extends AbstractController
{
    /**
     * @Route("/", name="_index")
     * @param ConversationRepository $conversationRepo
     * @return Response
     * @IsGranted("ROLE_ADMINISTRATEUR")
     */
    public function index(ConversationRepository $conversationRepo): Response
    {

        return $this->render('admin_conversation/index.html.twig', [
            'conversations'=> $conversationRepo->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="_show", methods={"GET","POST"})
     * @param Conversation $conversation
     * @param Request $request
     * @param UserInterface $user
     * @param UserRepository $userRepository
     * @IsGranted("ROLE_ADMINISTRATEUR")
     * @return Response
     */
    public function show(
        Conversation $conversation,
        Request $request,
        UserInterface $user,
        UserRepository $userRepository
    ): Response {
        $message = new Message();
        $login = $user->getUsername();
        $author = $userRepository->findOneBy(['login'=> $login]);
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $conversation->addMessage($message);
            $message->setUser($author);

            $data = $form->getData();
            $data->setDate(new dateTime());
            $data->setConversation($conversation);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($data);
            $entityManager->persist($conversation);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre message a bien été envoyé !'
            );
            return $this->redirectToRoute('admin_conversation_index');
        }
        return $this->render('admin_conversation/show.html.twig', [
            'conversation' => $conversation,
            'message' => $message,
            'messageForm' => $form->createView(),
        ]);
    }
}
