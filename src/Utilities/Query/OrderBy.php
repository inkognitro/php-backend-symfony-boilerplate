<?php declare(strict_types=1);

namespace App\Utilities\Query;

final class OrderBy
{
    private $orderByAttributes;

    /** @param $orderByAttributes OrderByAttribute[] */
    public function __construct(array $orderByAttributes)
    {
        $this->orderByAttributes = $orderByAttributes;
    }

    /** @return OrderByAttribute[] */
    public function toArray(): array
    {
        return $this->orderByAttributes;
    }

    public function addAttribute(string $attribute, string $orderDirection): self
    {
        return new self(array_merge($this->orderByAttributes, [
            new OrderByAttribute($attribute, $orderDirection)
        ]));
    }
}