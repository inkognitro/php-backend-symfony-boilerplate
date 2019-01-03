<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

final class UserFactory
{
   public function createSystemUser(): User
   {
       $userId = null;
       $role = User::SYSTEM_USER_ROLE;
       $languageId = 'en';
       return new User($userId, $role, $languageId);
   }
}