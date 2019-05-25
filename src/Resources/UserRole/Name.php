<?php declare(strict_types=1);

namespace App\Resources\UserRole;

use App\Resources\Attribute;

final class Name implements Attribute
{
    private $name;

    public static function getKey(): string
    {
        return 'userRole.name';
    }

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