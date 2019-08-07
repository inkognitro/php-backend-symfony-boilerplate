<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\Common\Application\Command\Params\Text;

final class UserParams
{
    private $id;
    private $emailAddress;
    private $password;
    private $username;
    private $roleId;

    public function __construct(
        ?Text $id,
        ?Text $emailAddress,
        ?Text $password,
        ?Text $username,
        ?Text $roleId
    ) {
        $this->id = $id;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->username = $username;
        $this->roleId = $roleId;
    }

    public function getId(): ?Text
    {
        return $this->id;
    }

    public function getEmailAddress(): ?Text
    {
        return $this->emailAddress;
    }

    public function getPassword(): ?Text
    {
        return $this->password;
    }

    public function getUsername(): ?Text
    {
        return $this->username;
    }

    public function getRoleId(): ?Text
    {
        return $this->roleId;
    }
}