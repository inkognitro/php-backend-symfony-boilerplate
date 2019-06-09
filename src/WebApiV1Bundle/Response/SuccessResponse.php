<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class SuccessResponse implements JsonResponse
{
    private $data;
    private $warnings;

    public function __construct(array $data, array $warnings)
    {
        $this->data = $data;
        $this->warnings = $warnings;
    }

    public function getHttpStatusCode(): int
    {
        return Response::HTTP_OK;
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