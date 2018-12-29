<?php declare(strict_types=1);

namespace App\Api\WebApiV1\Response;

use Symfony\Component\HttpFoundation\Response;

final class OkResponse implements JsonResponse
{
    private $data;
    private $warnings;

    public function __construct(array $data, array $warnings)
    {
        $this->data = $data;
        $this->warnings = $warnings;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_UNAUTHORIZED;
    }

    public function toJson(): string
    {
        $responseData = [];

        if(count($this->data) !== 0) {
            $responseData['data'] = $this->data;
        }

        if(count($this->warnings) !== 0) {
            $responseData['warnings'] = $this->warnings;
        }

        return json_encode($responseData);
    }
}