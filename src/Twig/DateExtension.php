<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class DateExtension extends AbstractExtension
{

    public function getFilters(): array
    {
        return [
            new TwigFilter('chat_date', [$this, 'formatChatDate']),
        ];
    }

    public function formatChatDate(\DateTimeInterface $dateTime): string
    {
        $today = new \DateTime('today');
        $yesterday = new \DateTime('yesterday');

        if ($dateTime->format('Y-m-d') === $today->format('Y-m-d')) {
            return 'Today';
        } elseif ($dateTime->format('Y-m-d') === $yesterday->format('Y-m-d')) {
            return 'Yesterday';
        } else {
            return $dateTime->format('F j, Y');
        }
    }
}
