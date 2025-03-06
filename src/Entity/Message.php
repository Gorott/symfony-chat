<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"chat:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"chat:read"})
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"chat:read"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"chat:read"})
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"chat:read"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Chat::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $chat;

    /**
     * @ORM\ManyToMany(targetEntity=MessageRead::class, mappedBy="message")
     */
    private $messageReads;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getChat(): ?Chat
    {
        return $this->chat;
    }

    public function setChat(?Chat $chat): self
    {
        $this->chat = $chat;

        return $this;
    }


    /**
     * @return Collection<int, MessageRead>
     */
    public function getMessageReads(): Collection
    {
        return $this->messageReads;
    }

    public function addMessageRead(MessageRead $messageRead): self
    {
        if (!$this->messageReads->contains($messageRead)) {
            $this->messageReads[] = $messageRead;
            $messageRead->addMessage($this);
        }

        return $this;
    }

    public function removeMessageRead(MessageRead $messageRead): self
    {
        if ($this->messageReads->removeElement($messageRead)) {
            $messageRead->removeMessage($this);
        }

        return $this;
    }
}
