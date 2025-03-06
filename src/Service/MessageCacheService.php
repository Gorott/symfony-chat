<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class MessageCacheService
{
    public function __construct(
        protected SessionInterface $session,
        protected MessageRepository $messageRepository
    )
    {
    }

    public function getMessages(int $chatId, string $startMessageId, int $limit = 50): array
    {
        $messageListKey = "messages_{$chatId}";


        $cachedMessageIds = $this->session->get($messageListKey, []);

        $validMessageIds = array_filter($cachedMessageIds, fn($id) => $id <= $startMessageId);
        $startMessageIndex = $validMessageIds ? array_search(max($validMessageIds), $cachedMessageIds, true) : false;

        if ($startMessageIndex !== false) {
            $cachedMessageIds = array_slice($cachedMessageIds, $startMessageIndex);
        } else {
            $cachedMessageIds = [];
        }

        $cachedMessages = [];
        foreach ($cachedMessageIds as $messageId) {
            $messageKey = "message_{$chatId}_{$messageId}";
            if ($this->session->has($messageKey)) {
                $cachedMessages[] = $this->session->get($messageKey);
            }
        }

        if (count($cachedMessages) >= $limit) {
            return $cachedMessages;
        }

        $lastCachedMessageId = !empty($cachedMessages) ? end($cachedMessages)->getId() : $startMessageId;

        $messages = $this->messageRepository->findMessagesBeforeId($chatId, $lastCachedMessageId);
        foreach ($messages as $message) {
            $this->addMessageToCache($message);
        }
        return array_merge($cachedMessages, $messages);
    }

    public function addMessageToCache(Message $message): void
    {
        $chatId = $message->getChat()->getId();
        $messageId = $message->getId();
        $messageKey = "message_{$chatId}_{$messageId}";
        $messageListKey = "messages_{$chatId}";

        $this->session->set($messageKey, $message);

        $cachedMessageIds = $this->session->get($messageListKey, []);
        if (!in_array($messageId, $cachedMessageIds)) {
            $cachedMessageIds[] = $messageId;
            $this->session->set($messageListKey, $cachedMessageIds);
        }
    }

    public function removeMessageFromCache(Message $message): void
    {
        $chatId = $message->getChat()->getId();
        $messageId = $message->getId();
        $messageKey = "message_{$chatId}_{$messageId}";
        $messageListKey = "messages_{$chatId}";

        $cachedMessageIds = $this->session->get($messageListKey, []);
        $cachedMessageIds = array_filter($cachedMessageIds, fn($id) => $id !== $messageId);
        $this->session->set($messageListKey, $cachedMessageIds);
        $this->session->remove($messageKey);
    }

    public function updateMessageInCache(Message $message): void
    {
        // just add the message again, it will overwrite the existing one
        $this->addMessageToCache($message);
    }
}