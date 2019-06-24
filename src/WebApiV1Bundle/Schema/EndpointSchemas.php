<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class EndpointSchemas
{
    private $schemas;

    /** @param $schemas EndpointSchema[] */
    private function __construct(array $schemas)
    {
        $this->schemas = $schemas;
    }

    /** @return EndpointSchema[] */
    public function toArray(): array
    {
        return $this->schemas;
    }
}