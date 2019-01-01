<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class BadRequestResponse implements JsonResponse
{
    private $errors;
    private $warnings;

    public function __construct(array $errors, array $warnings)
    {
        $this->errors = $errors;
        $this->warnings = $warnings;
    }

    public function getStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function toJson(): string
    {
        $responseData = [
            'message' => 'Bad request.'
        ];

        if(count($this->warnings) !== 0) {
            $responseData['warnings'] = $this->warnings;
        }

        if(count($this->errors) !== 0) {
            $responseData['errors'] = $this->errors;
        }

        return json_encode($responseData);
    }
}