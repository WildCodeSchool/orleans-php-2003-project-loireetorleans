<?php


namespace App\service;

use App\Entity\Conversation;
use App\Entity\User;
use App\Repository\ConversationRepository;

class ConversationManager
{
    private $conversationRepo;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepo = $conversationRepo;
    }

    public function conversationExist($docId, User $user) :bool
    {
        $conversation = $this->conversationRepo->findOneByConversation($docId, $user);
        return $conversation instanceof Conversation;
    }
}
