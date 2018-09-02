<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

final class NotFoundResponse implements JsonResponse
{
    private $message;

    private function __construct(array $message)
    {
        $this->message = $message;
    }

    public static function createFromMessage(array $message): self
    {
        return new self($message);
    }

    public function toJson(): string
    {
        return json_encode(['message' => $this->message]);
    }
}