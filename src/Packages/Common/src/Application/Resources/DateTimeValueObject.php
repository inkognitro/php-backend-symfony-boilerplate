<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources;

use App\Packages\Common\Application\DateTimeFactory;
use DateTimeImmutable;

abstract class DateTimeValueObject
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