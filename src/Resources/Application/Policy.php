<?php declare(strict_types=1);

namespace App\Resources\Application;

use App\Packages\Common\Application\Authorization\User as AuthUser;

interface Policy
{
    public function canEdit(AuthUser $authUser, Resource $resource, array $attributeNames);
}