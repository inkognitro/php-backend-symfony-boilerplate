<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class OkResponse implements JsonResponse
{
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function toJson(): string
    {
        return json_encode(['data' => $this->data]);
    }
}