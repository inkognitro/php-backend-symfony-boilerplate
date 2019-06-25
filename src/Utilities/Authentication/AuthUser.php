<?php declare(strict_types=1);

namespace App\Utilities\Authentication;

use App\Resources\AuditLogEvent\AuthUserPayload;
use App\Resources\Language\LanguageId;
use App\Resources\User\UserId;
use App\Resources\Role\RoleId;

final class AuthUser
{
    private $userId;
    private $roleId;
    private $languageId;

    public function __construct(?UserId $userId, RoleId $roleId, LanguageId $languageId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
        $this->languageId = $languageId;
    }

    public function getUserId(): ?UserId
    {
        return $this->userId;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }
    
    public function getLanguageId(): LanguageId
    {
        return $this->languageId;
    }

    public function isSystem(): bool
    {
        return $this->roleId->equals(RoleId::system());
    }

    public function isAdmin(): bool
    {
        return $this->roleId->equals(RoleId::admin());
    }

    public function toAuditLogEventAuthUserPayload(): AuthUserPayload
    {
        return AuthUserPayload::fromArray([
            'userId' => $this->userId->toString(),
            'roleId' => $this->roleId->toString(),
            'languageId' => $this->languageId->toString(),
        ]);
    }
}