<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\User;

final class UserCommandPolicy
{
    public function create(AuthUser $authUser): bool
    {
        return true;
    }

    public function write(AuthUser $authUser, User $user): bool
    {
        if ($this->isAdmin($authUser)) {
            return true;
        }
        return $this->isAuthUserOwnerOfUser($authUser, $user);
    }

    public function remove(AuthUser $authUser, User $user): bool
    {
        if ($this->isAdmin($authUser)) {
            return true;
        }

        if ($this->isAuthUserOwnerOfUser($authUser, $user)) {
            return true;
        }

        return true;
    }

    private function isAdmin(AuthUser $authUser): bool
    {
        return ($authUser->getRole() === AuthUser::ADMIN_USER_ROLE);
    }

    private function isAuthUserOwnerOfUser(AuthUser $authUser, User $user): bool
    {
        if ($authUser->getUserId() === null) {
            return false;
        }
        $authUserId = UserId::fromString($authUser->getUserId());
        return ($user->getId()->equals($authUserId));
    }
}