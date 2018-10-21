<?php declare(strict_types=1);

namespace App\Packages\Resources;

use App\Packages\Authentication\Application\User as AuthUser;
use App\Packages\Resources\Property\Properties;
use App\Packages\Resources\Property\Property;
use App\Packages\Resources\Validation\NotEmptyRule;

final class User implements Resource
{
    use ResourceTrait;

    private const PROPERTIES = [
        'uuid' => [
            'type' => Property::UUID,
            'validationRules' => [
                NotEmptyRule::class
            ]
        ],
        'username' => [
            'type' => Property::TEXT,
            'validationRules' => [
                NotEmptyRule::class
            ]
        ],
        'emailAddress' => [
            'type' => Property::TEXT,
            'validationRules' => [
                NotEmptyRule::class
            ]
        ],
    ];

    public function getReadablePropertiesByUser(AuthUser $user): Properties
    {
        return $this->getProperties();
    }

    public function getWritablePropertiesByUser(AuthUser $user): Properties
    {
        return $this->getProperties();
    }
}