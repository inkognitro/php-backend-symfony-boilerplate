<?php declare(strict_types=1);

namespace App\Resources\User\Application\Domain\Policy;

use App\Packages\Common\Application\Authorization\User as AuthUser;

final class CreateUserPolicy extends UserPolicy
{
    public function isAuthorized(AuthUser $authUser, Resource $resource): bool
    {
        return true;
    }
}