<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

use App\Packages\Common\Domain\DateTimeFactory;
use App\Packages\Common\Domain\DateTimeValueObject;

final class OccurredAt extends DateTimeValueObject
{
    public static function create(): self
    {
        return new self(DateTimeFactory::create());
    }
}