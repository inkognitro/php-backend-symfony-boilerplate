<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\ResourceAttributes\User;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;

final class CreatedAt extends NullableDateTimeAttribute
{
    public static function fromDateTime(\DateTimeImmutable $dateTime): self
    {
        return new self($dateTime);
    }

    public static function fromString(string $dateTime): self
    {
        /** @var $valueObject CreatedAt */
        $valueObject = self::fromInternalNullableString($dateTime);
        return $valueObject;
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return $this->nullableDateTime;
    }
}