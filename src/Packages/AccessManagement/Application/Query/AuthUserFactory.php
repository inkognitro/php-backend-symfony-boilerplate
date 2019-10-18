<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;

final class AuthUserFactory
{
    public function createSystemUser(): AuthUser
    {
        $userId = null;
        $roleId = RoleId::system();
        $languageId = LanguageId::english();
        return new AuthUser($userId, $roleId, $languageId);
    }
}