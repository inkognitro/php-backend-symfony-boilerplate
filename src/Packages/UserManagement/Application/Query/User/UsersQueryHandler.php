<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User;

use App\Packages\Common\Application\Query\Query;

interface UsersQueryHandler
{
    public function handle(Query $query): Users;
}