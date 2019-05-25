<?php declare(strict_types=1);

namespace App\Resources\UserRole;

use App\Resources\Attribute;

final class RoleId implements Attribute
{
    private $roleId;

    public static function getKey(): string
    {
        return 'userRole.id';
    }

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