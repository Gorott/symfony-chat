<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\ChatDTO;
use App\Entity\Chat;
use App\Form\CreateChatFormType;
use App\Form\SendMessageFormType;
use App\Repository\ChatRepository;
use App\Repository\MessageRepository;
use App\Service\MessageCacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ChatController extends AbstractController
{


    public function __construct(
        protected ChatRepository $chatRepository,
        protected MessageRepository $messageRepository,
        protected SerializerInterface $serializer,
        protected MessageCacheService $messageCacheService
    )
    {
    }

    /**
     * @Route("/chat/{id}", name="app_chat_room")
     */
    public function __invoke(string $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$this->chatRepository->isUserMemberOfChat($this->getUser(), $id)) {
            return $this->redirectToRoute('app_chat');
        }



        $chat = $this->chatRepository->find($id);

        $createChatForm = $this->createForm(CreateChatFormType::class);
        $sendMessageForm = $this->createForm(SendMessageFormType::class);
        $chatRooms = $this->chatRepository->findAllChatsByUser($this->getUser());
        $messages = $this->messageCacheService->getMessages($chat->getId(), (string)PHP_INT_MAX, PHP_INT_MAX);
        $messages = array_reverse($messages);

        return $this->render('chat.html.twig', [
            'messages' => $messages,
            'chatRooms' => $chatRooms,
            'currentRoom' => ChatDTO::fromEntity($chat)->toJsonObject(),
            'createChatForm' => $createChatForm->createView(),
            'sendMessageForm' => $sendMessageForm->createView(),
        ]);
    }
}
