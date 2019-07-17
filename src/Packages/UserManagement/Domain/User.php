<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\Role\Attributes\RoleId;

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