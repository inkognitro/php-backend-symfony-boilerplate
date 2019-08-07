<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserParamsValidation;

use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;

final class User
{
    private $userId;
    private $username;
    private $emailAddress;
    private $roleId;

    public function __construct(UserId $userId, Username $username, EmailAddress $emailAddress, RoleId $roleId)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->roleId = $roleId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }
}