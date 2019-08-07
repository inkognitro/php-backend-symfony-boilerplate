<?php declare(strict_types=1);

namespace App\Packages\Common\Utilities\Validation\Messages;

final class MustBeLowerCaseMessage implements Message
{
    public function getCode(): string
    {
        return 'mustBeLowerCase';
    }

    public function getMessage(): string
    {
        return 'must be lower case';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}