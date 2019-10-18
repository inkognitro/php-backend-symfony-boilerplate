<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\UserParamsValidation;

use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

final class ExistingUsersByValuesQuery
{
    private $userId;
    private $username;
    private $emailAddress;

    public function __construct(UserId $userId, ?Username $username, ?EmailAddress $emailAddress)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getUsername(): ?Username
    {
        return $this->username;
    }

    public function getEmailAddress(): ?EmailAddress
    {
        return $this->emailAddress;
    }
}
