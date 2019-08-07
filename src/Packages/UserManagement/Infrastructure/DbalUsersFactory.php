<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\UserParamsValidation\User;
use App\Packages\UserManagement\Domain\UserParamsValidation\Users;

final class DbalUsersFactory
{
    private $userFactory;

    public function __construct(DbalUserFactory $userFactory)
    {
        $this->userFactory = $userFactory;
    }

    public function createFromRows(array $rows): Users
    {
        $users = array_map(function(array $row): User {
            return $this->userFactory->createFromRow($row);
        }, $rows);
        return new Users($users);
    }
}