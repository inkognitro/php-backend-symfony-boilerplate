<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User;

use App\Packages\UserManagement\Application\Query\User\Attributes\VerifiedAt;
use App\Packages\Common\Application\Query\NotNull;
use App\Packages\Common\Application\Query\Query;

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