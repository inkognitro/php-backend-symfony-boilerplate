<?php declare(strict_types=1);

namespace App\Utilities\Validation\Messages;

final class DoesNotExistMessage implements Message
{
    public function getCode(): string
    {
        return 'doesNotExist';
    }

    public function getMessage(): string
    {
        return 'does not exist';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}