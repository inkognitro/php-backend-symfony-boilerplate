<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation\Message;

use App\Packages\Common\Application\Command\Validation\Message;

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