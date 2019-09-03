<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

final class ObjectParameterSchemaProperty
{
    private $name;
    private $property;
    private $isRequired;

    public function __construct(string $name, ParameterSchema $property, bool $isRequired)
    {
        $this->name = $name;
        $this->property = $property;
        $this->isRequired = $isRequired;
    }

    public function getPropertySchema(): ParameterSchema
    {
        return $this->property;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isRequired(): bool
    {
        return $this->isRequired;
    }
}