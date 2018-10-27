<?php declare(strict_types=1);

namespace App\Packages\Resources\Validation\Messages;

final class Messages
{
    private $messages;

    /** @param $messages Message[] */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }
}