<?php

declare(strict_types=1);

namespace App\Controller\Message;

use App\Entity\Message;
use App\Form\SendMessageFormType;
use App\Repository\MessageRepository;
use App\Service\MessageCacheService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditMessageController extends AbstractController
{

    public function __construct(
        protected MessageRepository $messageRepository,
        protected EntityManagerInterface $entityManager,
        protected MessageCacheService $messageCacheService
    )
    {
    }

    /**
     * @Route("/message/edit/{id}", name="app_edit_message")
     */
    public function __invoke(string $id, Request $request): Response
    {
        $message = $this->messageRepository->find($id);
        if (!$message) {
            throw $this->createNotFoundException();
        }

        if (!$this->isGranted('MSG_EDIT', $message)) {
            return new Response('You cannot edit this message', 403);
        }

        $messageForm = $this->createForm(SendMessageFormType::class, $message);
        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $message->setUpdatedAt(new \DateTime());

            $this->entityManager->flush();
            $this->messageCacheService->updateMessageInCache($message);

            return $this->redirectToRoute('app_chat_room', ['id' => $message->getChat()->getId()]);
        }

        return $this->redirectToRoute('app_chat_room', ['id' => $message->getChat()->getId()]);
    }
}
