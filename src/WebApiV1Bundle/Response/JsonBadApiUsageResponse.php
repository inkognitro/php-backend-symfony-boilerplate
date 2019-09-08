<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

final class JsonBadApiUsageResponse implements JsonResponse
{
    private $errors;

    private function __construct(array $errors)
    {
        $this->errors = $errors;
    }

    public static function getHttpStatusCode(): int
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public static function create(): self
    {
        return new self([]);
    }

    public function addErrors(array $errors)
    {
        return new self($errors);
    }

    public function toJson(): string
    {
        $responseData = [
            'message' => 'Bad api usage.'
        ];
        if (count($this->errors) !== 0) {
            $responseData['errors'] = $this->errors;
        }
        return json_encode($responseData);
    }

    public static function getSchema(): ResponseSchema
    {
        $description = 'Bad api usage response';
        return ResponseSchema::create(self::getHttpStatusCode(), ResponseSchema::JSON_CONTENT_TYPE, $description);
    }
}