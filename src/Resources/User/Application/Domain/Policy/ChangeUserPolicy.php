<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain\Policy;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\User\Application\User;

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