<?php declare(strict_types=1);

namespace App\Resources;

use App\Resources\Property\Properties;

final class User implements Resource
{
    private const PROPERTIES = [
        
    ];

    public function getProperties(): Properties
    {

    }

    public function getReadablePropertiesByUser(User $user): Properties
    {

    }

    public function getWritablePropertiesByUser(User $user): Properties
    {

    }
}