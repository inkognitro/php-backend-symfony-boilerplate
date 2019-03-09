<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Events;

use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Domain\DateTimeValueObject;

final class OccurredAt extends DateTimeValueObject
{
    public static function create(): self
    {
        return new self(DateTimeFactory::create());
    }
}