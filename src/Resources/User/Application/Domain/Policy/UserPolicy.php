<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain\Policy;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\User;

abstract class UserPolicy
{
    protected function isAuthUserOwnerOfUser(AuthUser $authUser, User $user): bool
    {
        if ($authUser->getUserId() === null) {
            return false;
        }
        $authUserId = UserId::fromString($authUser->getUserId());
        return ($user->getId()->isEqualTo($authUserId));
    }
}