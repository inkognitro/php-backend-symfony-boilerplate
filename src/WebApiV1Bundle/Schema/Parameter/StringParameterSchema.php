<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

final class StringParameterSchema implements ParameterSchema
{
    private const UUID_FORMAT = 'uuid';

    private $format;
    private $example;

    private function __construct(?string $format, ?string $example)
    {
        $this->format = $format;
        $this->example = $example;
    }

    public static function create(): self
    {
        return new self(null, null);
    }

    private function modifyByArray(array $data): self
    {
        return new self(
            ($data['format'] ?? $this->format),
            ($data['example'] ?? $this->example)
        );
    }

    public function setExample(string $example): self
    {
        return $this->modifyByArray([
            'example' => $example
        ]);
    }

    public function setUuidFormat(): self
    {
        return $this->modifyByArray([
            'format' => self::UUID_FORMAT,
        ]);
    }

    public function toRequestParameterOpenApiV2Array(): array
    {
        return $this->toOpenApiV2Array();
    }

    public function toResponseParameterOpenApiV2Array(): array
    {
        return $this->toOpenApiV2Array();
    }

    private function toOpenApiV2Array(): array
    {
        $data = [
            'type' => 'string',
        ];
        if(isset($this->format)) {
            $data['format'] = $this->format;
        }
        if(isset($this->example)) {
            $data['example'] = $this->example;
        }
        return $data;
    }
}