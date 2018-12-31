<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

use App\Packages\Common\Application\Resources\Resource;

final class User implements Resource
{
    private $id;
    private $username;
    private $emailAddress;

    public function __construct(string $id, string $username, string $emailAddress)
    {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
    }

    public function getId(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function getUsername(): Username
    {
        return Username::fromString($this->username);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromString($this->emailAddress);
    }
}