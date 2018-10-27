<?php declare(strict_types=1);

namespace App\Packages\Resources\Application;

use App\Packages\Common\Application\Authorization\User;
use App\Packages\Resources\Property\Properties;

final class PolicyEnforcementPoint
{
    public function isUserAuthorizedToReadProperties(User $authUser, Properties $properties): bool
    {
        return true;
    }

    public function isUserAuthorizedToWriteProperties(User $authUser, Properties $properties): bool
    {
        return true;
    }
}