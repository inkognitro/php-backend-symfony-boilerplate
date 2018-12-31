<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Policy;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\UserManagement\Application\Resources\User\User;

final class ChangeUserPolicy extends UserPolicy
{
    public function isAuthorized(AuthUser $authUser, User $user): bool
    {
        if ($authUser->hasEveryRight()) {
            return true;
        }
        return $this->isAuthUserOwnerOfUser($authUser, $user);
    }
}