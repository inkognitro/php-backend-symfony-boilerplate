<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Messages;

final class MustBeAUuidMessage implements Message
{
    public function getCode(): string
    {
        return 'mustBeAUuid';
    }

    public function getMessage(): string
    {
        return 'must be a uuid';
    }

    public function getPlaceholders(): array
    {
        return [];
    }
}