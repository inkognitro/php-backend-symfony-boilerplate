<?php declare(strict_types=1);

namespace App\Packages\Resources\Property;

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
}