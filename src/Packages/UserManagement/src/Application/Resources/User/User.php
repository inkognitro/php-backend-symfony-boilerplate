<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

use App\Packages\Common\Application\Resources\Resource;

final class User implements Resource
{
    private $id;
    private $username;
    private $emailAddress;
    private $password;
    private $role;

    public function __construct(
        UserId $id,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        Role $role
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->role = $role;
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
}