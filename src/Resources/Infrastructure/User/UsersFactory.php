<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Resources\Application\User\Users;

final class UsersFactory
{
    public function createFromRows(array $rows): Users
    {
        return Users::fromUsersArray([]);
    }
}