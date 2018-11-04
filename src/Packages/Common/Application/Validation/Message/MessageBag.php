<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Validation\Messages;

use App\Packages\Common\Application\Validation\Message;

final class MessageBag
{
    private $messages;

    public function addMessage(string $key, Message $message): void
    {
        $this->messages[$key] = $message;
    }

    public function addMessageBag(string $key, self $messageBag): void
    {
        $this->messages[$key] = $messageBag;
    }

    public function doesMessageKeyExist(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    public function getCount(): int
    {
        return count($this->messages);
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this->messages as $key => $value) {
            if ($value instanceof self && count($value->toArray()) === 0) {
                continue;
            }
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
                continue;
            }
            if ($value instanceof Message) {
                $array[$key] = [
                    'code' => get_class($value),
                    'message' => $value->getMessage()
                ];
            }
        }
        return $array;
    }
}