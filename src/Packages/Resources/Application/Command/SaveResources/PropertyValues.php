<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Command\SaveResources;

final class PropertyValues
{
    private $resourceClassName;
    private $propertyValues;

    public function __construct(string $resourceClassName, array $propertyValues)
    {
        $this->resourceClassName = $resourceClassName;
        $this->propertyValues = $propertyValues;
    }
}