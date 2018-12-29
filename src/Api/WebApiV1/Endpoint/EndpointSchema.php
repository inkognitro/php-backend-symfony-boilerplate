<?php declare(strict_types=1);

namespace App\Api\WebApiV1\Endpoint;

final class EndpointSchema
{
    private $schema;

    private function __construct($schema)
    {
        $this->schema = $schema;
    }

    public static function fromJsonFile(string $filename): self
    {
        $schema = json_decode(file_get_contents($filename), true);
        return new self($schema);
    }

    public function getUrlPart(): string
    {
        return $this->schema['endpoint']['urlPart'];
    }

    public function getMethod(): string
    {
        return $this->schema['endpoint']['method'];
    }
}