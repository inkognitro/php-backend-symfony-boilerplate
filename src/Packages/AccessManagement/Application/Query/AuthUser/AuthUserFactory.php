<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query\AuthUser;

use App\Packages\AccessManagement\Application\Query\AuthUser\Attributes\LanguageId;
use App\Packages\AccessManagement\Application\Query\AuthUser\Attributes\RoleId;

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