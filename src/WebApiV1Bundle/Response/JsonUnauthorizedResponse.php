<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

final class JsonUnauthorizedResponse implements JsonResponse
{
    public static function getHttpStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function toJson(): string
    {
        return json_encode(['message' => 'Unauthorized.']);
    }

    public static function getSchema(): ResponseSchema
    {
        $description = 'Unauthorized response';
        return new ResponseSchema(self::getHttpStatusCode(), ResponseSchema::JSON_CONTENT_TYPE, $description);
    }
}