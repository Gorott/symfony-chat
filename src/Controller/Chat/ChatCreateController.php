<?php

declare(strict_types=1);

namespace App\Controller\Chat;

use App\Entity\Chat;
use App\Form\CreateChatFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatCreateController extends AbstractController
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected UserRepository $userRepository,
    )
    {
    }

    /**
     * @Route("/chat/new", name="app_new_chat", methods={"POST"})
     */
    public function __invoke(Request $request): Response
    {
        $form = $this->createForm(CreateChatFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chatName = $form->get('chatName')->getData();
            $chat = new Chat();
            $chat->setName($chatName);
            $chat->addUser($this->getUser());
            $participants = explode(",", $form->get('participants')->get('tags')->getData());
            foreach ($participants as $participant) {
                $user = $this->userRepository->findOneBy(['username' => $participant]);
                if ($user && !$chat->getUsers()->contains($user)) {
                    $chat->addUser($user);
                }
            }

            $this->entityManager->persist($chat);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_chat_room', ['id' => $chat->getId()]);
        }

        return $this->redirectToRoute('app_chat_room');
    }
}
