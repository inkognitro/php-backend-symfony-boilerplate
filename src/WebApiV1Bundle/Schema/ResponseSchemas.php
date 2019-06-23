<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class ResponseSchemas
{
    private $schemas;

    /** @param $schemas ResponseSchema[] */
    public function __construct(array $schemas)
    {
        $this->schemas = $schemas;
    }

    /** @return ResponseSchema[] */
    public function toArray(): array
    {
        return $this->schemas;
    }

    public function add(ResponseSchema $schema): self
    {
        return new self(array_merge($this->schemas, [$schema]));
    }
}