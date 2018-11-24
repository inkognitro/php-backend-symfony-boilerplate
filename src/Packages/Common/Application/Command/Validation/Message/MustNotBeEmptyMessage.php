<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Validation\Messages;

use App\Packages\Common\Application\Command\Validation\Message;

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
}