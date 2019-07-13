<?php declare(strict_types=1);

namespace App\Utilities\Authentication;

use App\Resources\Application\Language\LanguageId;
use App\Resources\Application\Role\RoleId;

final class AuthUserFactory
{
    public function createSystemUser(): AuthUser
    {
        $userId = null;
        $roleId = RoleId::system();
        $languageId = LanguageId::english();
        return new AuthUser($userId, $roleId, $languageId);
    }

    public function createGuestUser(LanguageId $languageId): AuthUser
    {
        $userId = null;
        $role = RoleId::guest();
        return new AuthUser($userId, $role, $languageId);
    }
}