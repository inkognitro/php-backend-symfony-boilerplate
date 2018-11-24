<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

final class User
{
    public const SYSTEM_USER_ROLE = 'system';
    public const ADMIN_USER_ROLE = 'admin';
    public const GUEST_USER_ROLE = 'guest';
    public const DEFAULT_USER_ROLE = 'user';

    private $userId;
    private $role;
    private $languageId;

    public function __construct(?string $userId, string $role, string $languageId)
    {
        $this->userId = $userId;
        $this->role = $role;
        $this->languageId = $languageId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getRole(): string
    {
        return $this->role;
    }
    
    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function hasEveryRight(): bool
    {
        return ($this->role === self::SYSTEM_USER_ROLE || $this->role === self::ADMIN_USER_ROLE);
    }
}