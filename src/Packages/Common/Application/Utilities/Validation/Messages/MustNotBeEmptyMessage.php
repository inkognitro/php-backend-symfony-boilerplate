<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Messages;

final class MustNotBeEmptyMessage implements Message
{
    public function getCode(): string
    {
        return 'mustNotBeEmpty';
    }

    public function getMessage(): string
    {
        return 'must not be empty';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}