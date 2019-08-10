<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities\Validation\Messages;

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

    public function merge(self $that): self
    {
        return new self(array_merge($this->toArray(), $that->toArray()));
    }

    public function addMessage(string $key, Message $message): self
    {
        return new self(array_merge($this->messages, [
            $key => $message
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
        return $this->messages;
    }
}