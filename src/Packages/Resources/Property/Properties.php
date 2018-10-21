<?php declare(strict_types=1);

namespace App\Packages\Resources\Property;

final class Properties
{
    private $properties;

    /** @param $properties Property[] */
    public function __construct(array $properties)
    {
        $this->properties = $properties;
    }

    /** @return Property[] */
    public function toCollection(): array
    {
        return $this->properties;
    }
}