<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

use App\Packages\Common\Application\Validation\Message;

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
}