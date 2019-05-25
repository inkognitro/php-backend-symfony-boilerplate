<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

use App\Utilities\AuthUser;

final class UserFactory
{
   public function createSystemUser(): AuthUser
   {
       $userId = null;
       $role = AuthUser::SYSTEM_USER_ROLE;
       $languageId = 'en';
       return new AuthUser($userId, $role, $languageId);
   }
}