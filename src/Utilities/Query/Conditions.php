<?php declare(strict_types=1);

namespace App\Utilities\Query;

final class Conditions
{
    private $conditions;

    /** @param $conditions Condition[] */
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /** @return Condition[] */
    public function toArray(): array
    {
        return $this->conditions;
    }

    public function merge(self $that): self
    {
        return new self(array_merge($this->toArray(), $that->toArray()));
    }

    public function add(Condition $condition): self
    {
        return new self(array_merge($this->toArray(), [$condition]));
    }
}