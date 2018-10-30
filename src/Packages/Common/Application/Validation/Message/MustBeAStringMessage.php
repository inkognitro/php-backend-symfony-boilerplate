<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

final class MustBeAStringMessage implements Message
{
    public function getMessage(): string
    {
        return 'must be a string';
    }
}