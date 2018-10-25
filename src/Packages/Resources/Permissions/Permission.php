<?php declare(strict_types=1);

namespace App\Packages\Resources\Permissions;

use App\Packages\Authentication\Application\User as AuthUser;
use App\Packages\Resources\Property\Properties;

interface Permission
{
    public function canWriteProperties(AuthUser $user, Properties $properties): bool;
    public function getWritablePropertiesByUser(AuthUser $user): Properties;
}