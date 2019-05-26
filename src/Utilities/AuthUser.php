<?php declare(strict_types=1);

namespace App\Utilities;

use App\Resources\AuditLogEvent\AuthUserPayload;

final class AuthUser
{
    public const SYSTEM_USER_ROLE_ID = 'system';
    public const ADMIN_USER_ROLE_ID = 'admin';
    public const GUEST_USER_ROLE_ID = 'guest';
    public const NORMAL_USER_ROLE_ID = 'user';

    private $userId;
    private $roleId;
    private $languageId;

    public function __construct(?string $userId, string $roleId, string $languageId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->languageId = $languageId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getRoleId(): string
    {
        return $this->roleId;
    }
    
    public function getLanguageId(): string
    {
        return $this->languageId;
    }

    public function isSystem(): bool
    {
        return $this->roleId === self::SYSTEM_USER_ROLE_ID;
    }

    public function isAdmin(): bool
    {
        return $this->roleId === self::ADMIN_USER_ROLE_ID;
    }

    public function toAuditLogEventAuthUserPayload(): AuthUserPayload
    {
        return AuthUserPayload::fromArray([
            'userId' => $this->userId,
            'roleId' => $this->roleId,
            'languageId' => $this->languageId,
        ]);
    }
}