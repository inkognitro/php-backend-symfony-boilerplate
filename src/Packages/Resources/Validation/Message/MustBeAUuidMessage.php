<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Messages;

final class MustBeAUuidMessage implements Message
{
    public function getMessage(): string
    {
        return 'must be a uuid';
    }
}