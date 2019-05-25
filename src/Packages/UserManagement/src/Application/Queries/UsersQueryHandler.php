<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Queries;

interface UsersQueryHandler
{
    public function handle(UsersQuery $query): Users; //todo!
}