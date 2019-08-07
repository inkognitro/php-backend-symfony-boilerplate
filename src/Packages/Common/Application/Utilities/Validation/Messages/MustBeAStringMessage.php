<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Messages;

final class MustBeAStringMessage implements Message
{
    public function getCode(): string
    {
        return 'mustBeAString';
    }

    public function getMessage(): string
    {
        return 'must be a string';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}