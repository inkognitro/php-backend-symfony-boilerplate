<?php declare(strict_types=1);

namespace App\Utilities\Query;

interface Query
{
    public function getAttributes(): array;
    public function getCondition(): ?Condition;
}