<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

final class JsonBadRequestResponse implements JsonResponse
{
    private $errors;
    private $warnings;

    private function __construct(array $errors, array $warnings)
    {
        $this->errors = $errors;
        $this->warnings = $warnings;
    }

    public static function getHttpStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public static function create(): self
    {
        return new self([], []);
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

    public static function getSchema(): ResponseSchema
    {
        $description = 'Bad request response';
        return ResponseSchema::create(self::getHttpStatusCode(), ResponseSchema::JSON_CONTENT_TYPE, $description);
    }
}