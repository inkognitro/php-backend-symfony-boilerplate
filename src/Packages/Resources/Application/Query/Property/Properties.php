<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Query\Property;

final class Properties
{
    private $resourceClassName;
    private $properties;

    /** @param $properties Property[] */
    public function __construct(string $resourceClassName, array $properties)
    {
        $this->resourceClassName = $resourceClassName;
        $this->properties = $properties;
    }

    public function getResourceClassName(): string
    {
        return $this->resourceClassName;
    }

    /** @return Property[] */
    public function toCollection(): array
    {
        return $this->properties;
    }

    /** @return string[] */
    public function getNames(): array
    {
        $names = [];
        foreach($this->properties as $property) {
            $names[] = $property->getName();
        }
        return $names;
    }
}