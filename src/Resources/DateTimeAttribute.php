<?php declare(strict_types=1);

namespace App\Resources;

use App\Utilities\DateTimeFactory;
use DateTimeImmutable;

abstract class DateTimeAttribute implements Attribute
{
    private $dateTime;

    protected function __construct(DateTimeImmutable $dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function toDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    public function toString(): string
    {
        return DateTimeFactory::createString($this->dateTime);
    }

    public function isSame(self $createdAt): bool
    {
        return ($this->dateTime->getTimestamp() - $createdAt->toDateTime()->getTimestamp() === 0);
    }
}