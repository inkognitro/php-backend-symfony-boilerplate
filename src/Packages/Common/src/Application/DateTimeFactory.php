<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

use DateTimeImmutable;
use DateTimeZone;

final class DateTimeFactory
{
    public static function getTimezoneString(): string
    {
        return 'UTC';
    }

    public static function create(?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        return new DateTimeImmutable('now', self::getTimeZoneToApply($timeZone));
    }

    public static function createFromString(string $dateTimeString, ?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTimeString, self::getTimeZoneToApply($timeZone));
    }

    public static function createString(DateTimeImmutable $dateTime = null): string
    {
        return $dateTime->format('Y-m-d H:i:s');
    }

    private static function getTimeZoneToApply(?DateTimeZone $timeZone): DateTimeZone
    {
        return ($timeZone !== null ? $timeZone : new DateTimeZone(self::getTimezoneString()));
    }
}