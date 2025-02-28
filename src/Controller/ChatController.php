<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Chat;
use App\Form\CreateChatFormType;
use App\Form\SendMessageFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{

    /**
     * @Route("/chat/{id}", name="app_chat_room")
     */
    public function __invoke(string $id): Response
    {
        $chat = $this->getDoctrine()->getRepository(Chat::class)->find($id);
        $createChatForm = $this->createForm(CreateChatFormType::class);
        $sendMessageForm = $this->createForm(SendMessageFormType::class);

        $chatRooms = $this->getDoctrine()->getRepository(Chat::class)->findAll();
        return $this->render('chat.html.twig', [
            'messages' => [],
            'chatRooms' => $chatRooms,
            'currentRoom' => $chat,
            'createChatForm' => $createChatForm->createView(),
            'sendMessageForm' => $sendMessageForm->createView(),
        ]);
    }
}
