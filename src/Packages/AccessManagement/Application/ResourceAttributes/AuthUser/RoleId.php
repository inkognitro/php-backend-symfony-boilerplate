<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser;

use App\Packages\Common\Application\ResourceAttributes\Attribute;

final class RoleId implements Attribute
{
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

    public static function system(): self
    {
        return new self('system');
    }

    public static function admin(): self
    {
        return new self('admin');
    }

    public static function guest(): self
    {
        return new self('guest');
    }

    public static function user(): self
    {
        return new self('user');
    }

    public function equals(self $that): bool
    {
        return ($this->toString() === $that->toString());
    }
}