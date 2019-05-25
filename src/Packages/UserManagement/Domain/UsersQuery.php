<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Resources\User\EmailAddress;
use App\Resources\User\UserId;
use App\Resources\User\Username;

final class UsersQuery
{
    private $userId;
    private $username;
    private $emailAddress;

    private function __construct(?UserId $userId, ?Username $username, ?EmailAddress $emailAddress)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            ($data[UserId::getKey()] ?? null),
            ($data[Username::getKey()] ?? null),
            ($data[EmailAddress::getKey()] ?? null)
        );
    }

    public function getUserId(): ?UserId
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