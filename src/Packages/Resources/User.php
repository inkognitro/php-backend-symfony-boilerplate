<?php declare(strict_types=1);

namespace App\Packages\Resources;

use App\Packages\Resources\Property\Properties;
use App\Packages\Resources\Property\Property;
use App\Packages\Resources\Validation\Rule\EmptyOrEmailAddressRule;
use App\Packages\Resources\Validation\Rule\EmptyOrUuidRule;
use App\Packages\Resources\Validation\Rule\NotEmptyRule;
use App\Packages\Resources\Validation\Rules;

final class User extends AbstractResource
{
    private const PROPERTIES = [
        'uuid' => [
            'type' => Property::UUID,
            'validationRules' => [
                NotEmptyRule::class,
                EmptyOrUuidRule::class,
            ]
        ],
        'username' => [
            'type' => Property::TEXT,
            'validationRules' => [

            ]
        ],
        'emailAddress' => [
            'type' => Property::EMAIL,
            'validationRules' => [
                NotEmptyRule::class,
                EmptyOrEmailAddressRule::class,
            ]
        ],
    ];

    public function getProperties(): Properties
    {
        $properties = [];
        foreach (self::PROPERTIES as $name => $settings) {
            $properties[] = new Property(
                $name, $settings['type'], new Rules($settings['validationRules'])
            );
        }
        return new Properties(get_class($this), $properties);
    }
}