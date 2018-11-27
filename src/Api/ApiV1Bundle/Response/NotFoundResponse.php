<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class NotFoundResponse implements JsonResponse
{
    private $message;

    private function __construct(array $message)
    {
        $this->message = $message;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function toJson(): string
    {
        return json_encode(['message' => $this->message]);
    }
}