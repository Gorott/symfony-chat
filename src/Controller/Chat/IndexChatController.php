<?php

declare(strict_types=1);

namespace App\Controller\Chat;

use App\Entity\Chat;
use App\Form\CreateChatFormType;
use App\Repository\ChatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexChatController extends AbstractController
{

    public function __construct(
        protected ChatRepository $chatRepository
    )
    {
    }

    /**
     * @Route("/chat", name="app_chat")
     */
    public function __invoke(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $createChatForm = $this->createForm(CreateChatFormType::class);
        $chatRooms = $this->chatRepository->findAllChatsByUser($this->getUser());
        return $this->render('chat.html.twig', [
            'chatRooms' => $chatRooms,
            'currentRoom' => null,
            'createChatForm' => $createChatForm->createView(),
            'sendMessageForm' => null,
        ]);
    }
}
