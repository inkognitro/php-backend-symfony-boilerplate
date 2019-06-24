<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class ResponseSchema
{
    public const JSON_CONTENT_TYPE = 'application/json';
    public const HTML_CONTENT_TYPE = 'text/html';
    private $httpStatusCode;
    private $contentType;
    private $description;

    public function __construct(int $httpStatusCode, string $contentType, string $description)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->contentType = $contentType;
        $this->description = $description;
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function toOpenApiV2Array(): array
    {
        return [
            'description' => $this->description,
        ];
    }
}