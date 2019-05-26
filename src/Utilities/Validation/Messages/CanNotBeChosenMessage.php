<?php declare(strict_types=1);

namespace App\Utilities\Validation\Messages;

final class CanNotBeChosenMessage implements Message
{
    public function getCode(): string
    {
        return 'canNotBeChosen';
    }

    public function getMessage(): string
    {
        return 'can not be chosen';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}