<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserValidation;

use App\Resources\User\EmailAddress;
use App\Resources\User\User as UserResource;
use App\Resources\User\UserId;
use App\Resources\User\Username;

final class User implements UserResource
{
    private $userId;
    private $username;
    private $emailAddress;

    public function __construct(UserId $userId, Username $username, EmailAddress $emailAddress)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
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