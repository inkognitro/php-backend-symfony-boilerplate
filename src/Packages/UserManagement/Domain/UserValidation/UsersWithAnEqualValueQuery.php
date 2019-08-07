<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Packages\UserManagement\Domain\Users;
use App\Packages\UserManagement\Application\Query\User\Attributes\EmailAddress;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;

interface UsersWithAnEqualValueQuery
{
    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users;
}
