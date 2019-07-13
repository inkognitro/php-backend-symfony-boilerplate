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
}