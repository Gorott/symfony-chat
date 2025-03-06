<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\DTO\MessageDTO;
use App\Repository\ChatRepository;
use App\Repository\MessageRepository;
use App\Service\MessageCacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function MongoDB\BSON\toJSON;

class LoadMessageController extends AbstractController
{

    public function __construct(
        protected ChatRepository $chatRepository,
        protected MessageRepository $messageRepository,
        protected MessageCacheService $messageCacheService

    )
    {
    }

    /**
     * @Route("/chat/{id}/messages", name="app_load_messages")
     */
    public function __invoke(string $id, Request $request): Response
    {
        $chat = $this->chatRepository->find($id);

        if (!$chat) {
            throw $this->createNotFoundException('Chat not found');
        }

        $lastMessageId = $request->query->get('lastMessageId');
        $messages = $this->messageCacheService->getMessages($chat->getId(), $lastMessageId);
        $messageHtml = [];
        foreach ($messages as $message) {
            $messageObject = MessageDTO::fromEntity($message);
            $messageHtml[] = [
                'html' => $this->renderView('chat/message.html.twig', ['message' => $message]),
                'message' => $messageObject->toJsonObject()

            ];
        }

        return $this->json([
            'messages' => $messageHtml,
            'lastMessageId' => $messages ? $messages[count($messages) - 1]->getId() : null
        ]);
    }
}
