<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class NotFoundResponse implements JsonResponse
{
    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function toJson(): string
    {
        return json_encode(['message' => 'Resource not found.']);
    }
}