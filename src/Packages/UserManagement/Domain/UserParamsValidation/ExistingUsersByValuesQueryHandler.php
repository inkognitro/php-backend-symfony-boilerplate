<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserParamsValidation;

use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

interface ExistingUsersByValuesQueryHandler
{
    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users;
}
