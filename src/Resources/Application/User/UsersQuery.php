<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Resources\Application\User\Attributes\VerifiedAt;
use App\Utilities\Query\NotNull;
use App\Utilities\Query\Query;

final class UsersQuery extends Query
{
    public static function createFromVerifiedUsers(array $attributes): self
    {
        /** @var $query self */
        $query = self::create($attributes);
        $query = $query->andWhere(new NotNull(VerifiedAt::class));
        return $query;
    }
}