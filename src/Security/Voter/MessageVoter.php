<?php

namespace App\Security\Voter;

use App\Entity\Message;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageVoter extends Voter
{
    public const EDIT = 'MSG_EDIT';
    public const DELETE = 'MSG_DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof Message;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** @var Message $message */
        $message = $subject;
        // ... (check conditions and return true to grant permission) ...
        return match($attribute) {
            self::DELETE => $this->canDelete($message, $user),
            self::EDIT => $this->canEdit($message, $user),
            default => false,
        };
    }

    private function canDelete(Message $message, UserInterface $user): bool
    {
        return $user === $message->getAuthor();
    }

    private function canEdit(Message $message, UserInterface $user): bool
    {
        return $user === $message->getAuthor();
    }
}
