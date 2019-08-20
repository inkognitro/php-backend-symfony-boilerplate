<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\ResourceAttributes\User;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;
use DateTimeImmutable;

final class VerifiedAt extends NullableDateTimeAttribute
{
    public static function fromNullableString(?string $dateTime): self
    {
        /** @var $valueObject VerifiedAt */
        $valueObject = self::fromInternalNullableString($dateTime);
        return $valueObject;
    }

    public static function fromNullableDateTime(?DateTimeImmutable $dateTime): self
    {
        /** @var $valueObject VerifiedAt */
        $valueObject = new self($dateTime);
        return $valueObject;
    }

    public function toNullableString(): ?string
    {
        return $this->toInternalNullableString();
    }

    public function toNullableDateTime(): ?DateTimeImmutable
    {
        return $this->nullableDateTime;
    }
}