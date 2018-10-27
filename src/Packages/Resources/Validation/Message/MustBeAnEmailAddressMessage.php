<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Messages;

final class MustBeAnEmailAddressMessage implements Message
{
    public function getMessage(): string
    {
        return 'must be an email address';
    }
}