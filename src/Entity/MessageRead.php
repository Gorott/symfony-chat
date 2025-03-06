<?php

namespace App\Entity;

use App\Repository\MessageReadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageReadRepository::class)
 */
class MessageRead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Message::class, inversedBy="messageReads")
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $readBy;

    public function __construct()
    {
        $this->message = new ArrayCollection();
        $this->user = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->message->contains($message)) {
            $this->message[] = $message;
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        $this->message->removeElement($message);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    public function getReadBy(): ?bool
    {
        return $this->readBy;
    }

    public function setReadBy(bool $readBy): self
    {
        $this->readBy = $readBy;

        return $this;
    }
}
