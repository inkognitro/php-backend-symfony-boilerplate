<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

final class RolesRepository
{
    public const SYSTEM_USER_ROLE = 'system';
    public const ADMIN_USER_ROLE = 'admin';
    public const GUEST_USER_ROLE = 'guest';
    public const DEFAULT_USER_ROLE = 'user';
    
    public function findAll(): array
    {
        return [
            self::SYSTEM_USER_ROLE,
            self::ADMIN_USER_ROLE,
            self::GUEST_USER_ROLE,
            self::DEFAULT_USER_ROLE
        ];
    }
}