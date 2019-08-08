<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Utilities;

use DateInterval;
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

    public static function createString(): string
    {
        return self::create()->format('Y-m-d H:i:s');
    }

    public static function addMinutes(DateTimeImmutable $dateTime, int $minutesToAdd): DateTimeImmutable
    {
        return $dateTime->add(new DateInterval('PT' . $minutesToAdd . 'M'));
    }

    public static function createFromString(string $dateTimeString, ?DateTimeZone $timeZone = null): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTimeString, self::getTimeZoneToApply($timeZone));
    }

    private static function getTimeZoneToApply(?DateTimeZone $timeZone): DateTimeZone
    {
        return ($timeZone !== null ? $timeZone : new DateTimeZone(self::getTimezoneString()));
    }
}