<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

final class UserParams
{
    private $id;
    private $emailAddress;
    private $password;
    private $username;
    private $roleId;

    private function __construct(
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

    public static function fromArray(array $data): self
    {
        return new self(
            ($data[UserId::class] ?? null),
            ($data[EmailAddress::class] ?? null),
            ($data[Password::class] ?? null),
            ($data[Username::class] ?? null),
            ($data[RoleId::class] ?? null)
        );
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