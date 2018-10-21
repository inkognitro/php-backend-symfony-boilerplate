<?php declare(strict_types=1);

namespace App\Packages\Resources;

use App\Packages\Resources\Property\Properties;
use App\Packages\Resources\Property\Property;

trait ResourceTrait
{
    public function getProperties(): Properties
    {
        $properties = [];
        foreach (self::PROPERTIES as $name => $settings) {
            $properties[] = new Property(
                $name, $settings['type'], $settings['validationRules']
            );
        }
        return new Properties($properties);
    }
}