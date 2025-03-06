<?php

namespace App\DTO;

use App\Entity\Message;

class MessageDTO
{
    public string $id;
    public string $content;
    public string $chatId;
    public string $authorId;
    public string $createdAt;
    public string $updatedAt;

    public function __construct(string $id, string $content, string $chatId, string $authorId, string $createdAt, string $updatedAt)
    {
        $this->id = $id;
        $this->content = $content;
        $this->chatId = $chatId;
        $this->authorId = $authorId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'chatId' => $this->chatId,
            'authorId' => $this->authorId,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt
        ];
    }

    public static function fromEntity(Message $entity): self
    {
        return new self(
            $entity->getId(),
            $entity->getContent(),
            $entity->getChat()->getId(),
            $entity->getAuthor()->getId(),
            $entity->getCreatedAt()->format('Y-m-d H:i:s'),
            $entity->getUpdatedAt() ? $entity->getUpdatedAt()->format('Y-m-d H:i:s') : ''
        );
    }
    public function toJsonObject(): string
    {
        return json_encode($this->toArray());
    }
}