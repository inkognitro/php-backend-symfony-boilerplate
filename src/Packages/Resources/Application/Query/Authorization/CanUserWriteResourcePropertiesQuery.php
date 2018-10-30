<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Query\Authorization;

use App\Packages\Common\Application\Authorization\User;
use App\Packages\Resources\Application\Query\Property\Properties;
use App\Packages\Resources\Application\User;

final class CanUserWriteResourcePropertiesQuery
{
    public function execute(User $authUser, Properties $properties): bool
    {
        
    }
}