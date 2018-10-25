<?php declare(strict_types=1);

namespace App\Packages\Resources;

use App\Packages\Resources\Property\Property;

final class User extends AbstractResource
{
    const PROPERTIES = [
        'uuid' => Property::UUID,
        'username' => Property::TEXT,
        'emailAddress' => Property::EMAIL,
    ];
}