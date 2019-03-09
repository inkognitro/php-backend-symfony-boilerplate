<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Policies;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\User;

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