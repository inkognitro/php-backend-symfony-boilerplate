<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Packages\UserManagement\Domain\Users;
use App\Resources\User\EmailAddress;
use App\Resources\User\UserId;
use App\Resources\User\Username;

interface UsersWithAnEqualValueQuery
{
    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users;
}
