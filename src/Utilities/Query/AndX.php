<?php declare(strict_types=1);

namespace App\Utilities\Query;

final class AndX implements Condition
{
    private $conditions;

    public function __construct(Conditions $conditions)
    {
        $this->conditions = $conditions;
    }

    public function getConditions(): Conditions
    {
        return $this->conditions;
    }

    public function merge(self $that): self
    {
        return new self($this->getConditions()->merge($that->getConditions()));
    }

    public function addCondition(Condition $condition): self
    {
        return new self($this->conditions->add($condition));
    }
}