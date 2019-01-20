<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Application\Resources\DateTimeValueObject;

final class OccurredAt extends DateTimeValueObject
{
    public static function create(): self
    {
        return new self(DateTimeFactory::create());
    }
}