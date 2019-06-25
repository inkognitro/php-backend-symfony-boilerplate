<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Resources\User\EmailAddress;
use App\Resources\User\User as UserResource;
use App\Resources\User\UserId;
use App\Resources\User\Username;
use App\Resources\Role\RoleId;

final class User implements UserResource
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