<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\ResponseParameter;

final class ObjectResponseParameterSchema implements ResponseParameter
{
    private $properties;

    /** @param $properties ObjectResponseParameterProperty[] */
    private function __construct(array $properties)
    {
        $this->properties = $properties;
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

    public function addProperty(string $propertyName, ResponseParameter $parameter, bool $isRequired = false): self
    {
        $property = new ObjectResponseParameterProperty($propertyName, $parameter, $isRequired);
        return $this->modifyByArray([
            'properties' => array_merge($this->properties, [$propertyName => $property]),
        ]);
    }

    public function toOpenApiV2Array(): array
    {
        $properties = [];
        $required = [];
        foreach ($this->properties as $property) {
            $properties[$property->getName()] = $property->getProperty()->toOpenApiV2Array();
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
}