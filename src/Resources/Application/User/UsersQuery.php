<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Utilities\Query\Condition;
use App\Utilities\Query\Query;

final class UsersQuery implements Query
{
    private $attributes;
    private $condition;

    private function __construct(array $attributes, Condition $condition)
    {
        $this->attributes = $attributes;
        $this->condition = $condition;
    }

    public static function createFromActivatedUsers(array $attributes, Condition $condition): self
    {
        //todo add condition for active users to condition
        return new self($attributes, $condition);
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getCondition(): Condition
    {
        return $this->condition;
    }
}