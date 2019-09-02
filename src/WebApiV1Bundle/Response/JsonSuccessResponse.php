<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\Schema\ResponseSchema;
use Symfony\Component\HttpFoundation\Response;

final class JsonSuccessResponse implements JsonResponse
{
    private $data;
    private $warnings;

    private function __construct(array $data, array $warnings)
    {
        $this->data = $data;
        $this->warnings = $warnings;
    }

    public static function fromData(array $data): self
    {
        $warnings = [];
        return new self($data, $warnings);
    }

    public static function getHttpStatusCode(): int
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

    public static function getSchema(): ResponseSchema
    {
        $description = 'Success response';
        return ResponseSchema::create(self::getHttpStatusCode(), ResponseSchema::JSON_CONTENT_TYPE, $description);
    }
}