<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Messages;

final class DoesAlreadyExistMessage implements Message
{
    public function getCode(): string
    {
        return 'doesAlreadyExist';
    }

    public function getMessage(): string
    {
        return 'does already exist';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}