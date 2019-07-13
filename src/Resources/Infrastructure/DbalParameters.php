<?php declare(strict_types=1);

namespace App\Resources\Infrastructure;

final class DbalParameters
{
    private $parameters;

    /** @param $parameters DbalParameter[] */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /** @return DbalParameter[] */
    public function toArray(): array
    {
        return $this->parameters;
    }

    public function toQueryBuilderParameters(): array
    {
        $dbalParameters = [];
        foreach($this->parameters as $parameter) {
            $dbalParameters[':' . $parameter->getName()] = $parameter->getValue();
        }
        return $dbalParameters;
    }

    public function toQueryBuilderParameterTypes(): array
    {
        $types = [];
        foreach($this->parameters as $parameter) {
            $types[] = $parameter->getType();
        }
        return $types;
    }

    public function merge(self $that): self
    {

    }
}