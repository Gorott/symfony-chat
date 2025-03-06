<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Repository\MessageRepository;
use App\Service\MessageCacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DeleteMessageController extends AbstractController
{

    public function __construct(
        protected MessageRepository $messageRepository,
        protected EntityManagerInterface $entityManager,
        protected MessageCacheService $messageCacheService
    )
    {
    }
    /**
     * @Route("/message/delete/{id}", name="app_delete_message")
     */
    public function __invoke(string $id): Response
    {
        $message = $this->messageRepository->find($id);
        if (!$message) {
            throw $this->createNotFoundException();
        }

        if (!$this->isGranted('MSG_DELETE', $message)) {
            return new Response('You cannot delete this message', 403);
        }

        $this->messageCacheService->removeMessageFromCache($message);

        $this->entityManager->remove($message);
        $this->entityManager->flush();
        $chatId = $message->getChat()->getId();
        return new RedirectResponse($this->generateUrl('app_chat_room', ['id' => $chatId]));
    }
}
