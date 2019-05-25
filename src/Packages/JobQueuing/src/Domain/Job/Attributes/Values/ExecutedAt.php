<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Job\Attributes\Values;

use App\Packages\Common\Domain\DateTimeFactory;
use App\Packages\Common\Domain\DateTimeValueObject;

final class ExecutedAt extends DateTimeValueObject
{
    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}