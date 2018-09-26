<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Response;

final class BadRequestResponse implements JsonResponse
{
    private $message;

    private function __construct(string $message)
    {
        $this->message = $message;
    }

    public static function createFromMessage(string $message): self
    {
        return new self($message);
    }

    public function toJson(): string
    {
        return json_encode(['message' => $this->message]);
    }
}