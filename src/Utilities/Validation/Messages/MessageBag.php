<?php declare(strict_types=1);

namespace App\Utilities\Validation\Messages;

final class MessageBag
{
    private $messages;

    private function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    public static function create(): self
    {
        return new self([]);
    }

    public function addMessage(string $key, Message $message): self
    {
        return new self(array_merge($this->messages, [
            $key => $message
        ]));
    }

    public function addMessageBag(string $key, self $messageBag): self
    {
        return new self(array_merge($this->messages, [
            $key => $messageBag
        ]));
    }

    public function hasKey(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    public function hasOneOfKeys(array $keys): bool
    {
        foreach ($keys as $key) {
            if ($this->hasKey($key)) {
                return true;
            }
        }
        return false;
    }

    public function isEmpty(): bool
    {
        return (count($this->messages) === 0);
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
                    'code' => $value->getCode(),
                    'message' => $value->getMessage(),
                    'placeholders' => $value->getPlaceholders(),
                ];
            }
        }
        return $array;
    }
}