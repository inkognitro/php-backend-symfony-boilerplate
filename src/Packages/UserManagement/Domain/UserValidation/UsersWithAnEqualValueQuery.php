<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Packages\UserManagement\Domain\Users;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;

interface UsersWithAnEqualValueQuery
{
    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users;
}
