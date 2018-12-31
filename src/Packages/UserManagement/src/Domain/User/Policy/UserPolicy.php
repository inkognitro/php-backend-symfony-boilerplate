<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Policy;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\User;

abstract class UserPolicy
{
    protected function isAuthUserOwnerOfUser(AuthUser $authUser, User $user): bool
    {
        if ($authUser->getUserId() === null) {
            return false;
        }
        $authUserId = UserId::fromString($authUser->getUserId());
        return ($user->getId()->isEqual($authUserId));
    }
}