<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

final class OrX implements Condition
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
}