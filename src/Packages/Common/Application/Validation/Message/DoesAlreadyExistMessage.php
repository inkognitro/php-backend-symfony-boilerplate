<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

use App\Packages\Common\Application\Validation\Message;

final class DoesAlreadyExistMessage implements Message
{
    public function getCode(): string
    {
        return 'doesAlreadyExist';
    }

    public function getMessage(): string
    {
        return 'does already exist';
    }
}