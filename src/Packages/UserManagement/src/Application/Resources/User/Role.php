<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

final class Role
{
    private $role;

    private function __construct(string $role)
    {
        $this->role = $role;
    }

    public static function fromString(string $role): self
    {
        return new self($role);
    }

    public function toString(): string
    {
        return $this->role;
    }

    public function isEqual(self $role): bool
    {
        return (strcasecmp($role->toString(), $this->toString()) === 0);
    }

    public function isSame(self $role): bool
    {
        return ($role->toString() === $this->toString());
    }
}