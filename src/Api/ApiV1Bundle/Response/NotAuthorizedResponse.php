<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Response;

final class NotAuthorizedResponse implements JsonResponse
{
    public function toJson(): string
    {
        return json_encode(['message' => 'Not authorized.']);
    }
}