<?php declare(strict_types=1);

namespace App\Packages\Resources\Permissions;

use App\Packages\Authentication\Application\User as AuthUser;
use App\Packages\Resources\Property\Properties;

final class UserPermission implements Permission
{
    public function canWriteProperties(AuthUser $user, Properties $properties): bool
    {
        return true;
    }

    public function getWritablePropertiesByUser(AuthUser $user): Properties
    {
        return new Properties([]);
    }
}