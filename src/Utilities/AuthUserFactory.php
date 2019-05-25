<?php declare(strict_types=1);

namespace App\Utilities;

final class AuthUserFactory
{
   public function createSystemUser(): AuthUser
   {
       $userId = null;
       $role = AuthUser::SYSTEM_USER_ROLE;
       $languageId = 'en';
       return new AuthUser($userId, $role, $languageId);
   }
}