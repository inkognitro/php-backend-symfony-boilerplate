<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Role;

final class RoleName
{
    private $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $role): self
    {
        return new self($role);
    }

    public function toString(): string
    {
        return $this->name;
    }
}