<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

final class MustBeAnEmailAddressMessage implements Message
{
    public function getCode(): string
    {
        return 'mustBeAnEmailAddress';
    }

    public function getMessage(): string
    {
        return 'must be an email address';
    }
}