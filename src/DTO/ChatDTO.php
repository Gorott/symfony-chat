<?php

namespace App\DTO;

class ChatDTO
{
    public $id;
    public $name;
    public $users;


    public function __construct($id, $name, $users)
    {
        $this->id = $id;
        $this->name = $name;
        $this->users = $users;
    }

    public static function fromEntity($entity): self
    {
        $users = [];
        foreach ($entity->getUsers() as $user) {
            $users[] = $user->getId();
        }


        return new self(
            $entity->getId(),
            $entity->getName(),
            $users,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'users' => $this->users,
        ];
    }
    public function toJsonObject(): string
    {
        return json_encode($this->toArray());
    }
}