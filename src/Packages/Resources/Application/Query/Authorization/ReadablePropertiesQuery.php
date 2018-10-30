<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\Query\Authorization;

use App\Packages\Common\Application\Authorization\User;
use App\Packages\Resources\Application\Query\Property\Properties;

final class ReadablePropertiesQuery
{
    public function execute(User $authUser, string $resourceClassName): Properties
    {
        return new Properties($resourceClassName, []); //todo implement getProperties by Policy
    }
}