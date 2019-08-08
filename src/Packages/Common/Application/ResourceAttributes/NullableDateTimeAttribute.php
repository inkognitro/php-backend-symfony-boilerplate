<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes;

use App\Packages\Common\Application\Utilities\DateTimeFactory;
use DateTimeImmutable;

abstract class NullableDateTimeAttribute implements Attribute
{
    protected $nullableDateTime;

    protected function __construct(?DateTimeImmutable $nullableDateTime)
    {
        $this->nullableDateTime = $nullableDateTime;
    }

    protected static function fromInternalNullableString(?string $dateTime): self
    {
        if($dateTime === null) {
            return new static($dateTime);
        }
        return new static(DateTimeFactory::createFromString($dateTime));
    }
}