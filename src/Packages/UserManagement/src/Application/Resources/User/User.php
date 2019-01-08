<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Resources\ValueObjects\CreatedAt;
use App\Packages\Common\Application\Resources\ValueObjects\UpdatedAt;

final class User extends AbstractResource
{
    private $id;
    private $username;
    private $emailAddress;
    private $password;
    private $role;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        UserId $id,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        Role $role,
        ?CreatedAt $createdAt,
        ?UpdatedAt $updatedAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->role = $role;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }
}