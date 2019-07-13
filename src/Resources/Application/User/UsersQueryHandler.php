<?php declare(strict_types=1);

namespace App\Resources\Application\User;

interface UsersQueryHandler
{
    public function handle(UsersQuery $query): Users;
}