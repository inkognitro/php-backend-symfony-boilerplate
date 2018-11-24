<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation\Messages;

use App\Packages\Common\Application\Command\Validation\Message;

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
}