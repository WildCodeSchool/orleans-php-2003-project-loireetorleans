<?php


namespace App\service;

use App\Entity\Conversation;
use App\Repository\ConversationRepository;

class ConversationManager
{
    private $conversationRepo;

    public function __construct(ConversationRepository $conversationRepo)
    {
        $this->conversationRepo = $conversationRepo;
    }

    public function conversationExist($docId, $userId) :bool
    {
        $conversation = $this->conversationRepo->findOneByConversation($docId, $userId);
        return $conversation instanceof Conversation;
    }
}
