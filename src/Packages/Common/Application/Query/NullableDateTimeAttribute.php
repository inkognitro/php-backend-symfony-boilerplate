<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

use DateTimeImmutable;

abstract class NullableDateTimeAttribute implements Attribute
{
    private $nullableDateTime;

    protected function __construct(?DateTimeImmutable $nullableDateTime)
    {
        $this->nullableDateTime = $nullableDateTime;
    }

    protected function toNullableDateTime(): ?DateTimeImmutable
    {
        return $this->nullableDateTime;
    }
}