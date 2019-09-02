<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

use App\WebApiV1Bundle\Schema\ResponseParameter\ResponseParameter;

final class ResponseSchema
{
    public const JSON_CONTENT_TYPE = 'application/json';
    public const HTML_CONTENT_TYPE = 'text/html';
    private $httpStatusCode;
    private $contentType;
    private $description;
    private $responseParameter;

    private function __construct(int $httpStatusCode, string $contentType, string $description, ?ResponseParameter $responseParameter)
    {
        $this->httpStatusCode = $httpStatusCode;
        $this->contentType = $contentType;
        $this->description = $description;
        $this->responseParameter = $responseParameter;
    }

    public static function create(int $httpStatusCode, string $contentType, string $description): self
    {
        return new self($httpStatusCode, $contentType, $description, null);
    }

    public function getHttpStatusCode(): int
    {
        return $this->httpStatusCode;
    }

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function setResponseParameters(ResponseParameter $responseParameter): self
    {
        return $this->modifyByArray(['responseParameter' => $responseParameter]);
    }

    public function toOpenApiV2Array(): array
    {
        $data = [
            'description' => $this->description,
        ];
        if($this->responseParameter) {
            $data['schema'] = $this->responseParameter->toOpenApiV2Array();
        }
        return $data;
    }

    private function modifyByArray(array $data): self
    {
        return new self(
            ($data['httpStatusCode'] ?? $this->httpStatusCode),
            ($data['contentType'] ?? $this->contentType),
            ($data['description'] ?? $this->description),
            ($data['responseParameter'] ?? $this->responseParameter)
        );
    }
}