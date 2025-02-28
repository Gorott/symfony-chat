<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Entity\Chat;
use App\Entity\Message;
use App\Form\SendMessageFormType;
use App\Repository\ChatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SendMessageController extends AbstractController
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected ChatRepository $chatRepository
    )
    {
    }
    /**
     * @Route("/chat/{id}/send-message", name="app_send_message", methods={"POST"})
     */
    public function __invoke(string $id, Request $request): Response
    {
        $chat = $this->chatRepository->find($id);

        if (!$chat) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(SendMessageFormType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $content = $form->get('content')->getData();

            $message = new Message();
            $message->setContent($content);
            $message->setAuthor($this->getUser());
            $message->setChat($chat);

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_chat_room', ['id' => $chat->getId()]);
        }

        return $this->redirectToRoute('app_chat_room', ['id' => $chat->getId()]);
    }
}
