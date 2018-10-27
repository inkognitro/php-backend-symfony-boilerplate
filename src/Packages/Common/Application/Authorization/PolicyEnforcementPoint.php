<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

final class PolicyEnforcementPoint
{
    public function isAuthorized(User $user, Permission $permission): bool
    {
        return true;
    }
}