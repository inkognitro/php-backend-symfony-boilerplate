<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Resources\Application\User\Attributes\VerifiedAt;
use App\Utilities\Query\AndX;
use App\Utilities\Query\Conditions;
use App\Utilities\Query\NotNull;
use App\Utilities\Query\Query;

final class UsersQuery extends Query
{
    public static function createFromVerifiedUsers(array $attributes): self
    {
        $condition = new AndX(new Conditions([new NotNull(VerifiedAt::class)]));
        /** @var $query self */
        $query = self::create($attributes)->andWhere($condition);
        return $query;
    }
}