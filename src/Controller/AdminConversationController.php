<?php

namespace App\Controller;

use App\Repository\ConversationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
