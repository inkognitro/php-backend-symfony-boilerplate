<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

final class ObjectParameterSchema implements ParameterSchema
{
    private $properties;

    /** @param $properties ObjectParameterSchemaProperty[] */
    private function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /** @return ObjectParameterSchemaProperty[] */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public static function create(): self
    {
        return new self([]);
    }

    private function modifyByArray(array $data): self
    {
        return new self(
            ($data['properties'] ?? $this->properties)
        );
    }

    public function addProperty(string $propertyName, ParameterSchema $parameter, bool $isRequired = false): self
    {
        $property = new ObjectParameterSchemaProperty($propertyName, $parameter, $isRequired);
        return $this->modifyByArray([
            'properties' => array_merge($this->properties, [$propertyName => $property]),
        ]);
    }

    private function toOpenApiV2Array(): array
    {
        $properties = [];
        $required = [];
        foreach ($this->properties as $property) {
            $properties[$property->getName()] = $property->getPropertySchema()->toResponseParameterOpenApiV2Array();
            if ($property->isRequired()) {
                $required[] = $property->getName();
            }
        }
        $data = [
            'type' => 'object',
            'properties' => $properties,
        ];
        if (count($required)) {
            $data['required'] = $required;
        }
        return $data;
    }

    public function toResponseParameterOpenApiV2Array(): array
    {
        return $this->toOpenApiV2Array();
    }

    public function toRequestParameterOpenApiV2Array(): array
    {
        return $this->toOpenApiV2Array();
    }
}