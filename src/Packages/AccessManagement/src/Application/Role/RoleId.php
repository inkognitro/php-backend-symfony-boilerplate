<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Role;

final class RoleId
{
    public const KEY = Role::KEY . '.id';

    private $roleId;

    private function __construct(string $roleId)
    {
        $this->roleId = $roleId;
    }

    public static function fromString(string $roleId): self
    {
        return new self($roleId);
    }

    public function toString(): string
    {
        return $this->roleId;
    }
}