<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Resources\Application\Policy;
use App\Resources\Application\WrongResourceException;

final class UserPolicy implements Policy
{
    public function canEdit(AuthUser $authUser, Resource $user, array $attributeNames): bool
    {
        if(!$user instanceof User) {
            throw new WrongResourceException('$user is not an instance of ' . User::class);
        }

        if ($authUser->getRole() === AuthUser::ADMIN_USER_ROLE) {
            return true;
        }

        if (strcasecmp($authUser->getUserId(), $user->getId()) === 0) {
            return true;
        }

        return false;
    }
}