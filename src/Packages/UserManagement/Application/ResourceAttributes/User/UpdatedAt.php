<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\ResourceAttributes\User;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;

final class UpdatedAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'updatedAt';
    }

    public static function fromString(string $dateTime): self
    {
        /** @var $valueObject UpdatedAt */
        $valueObject = self::fromInternalNullableString($dateTime);
        return $valueObject;
    }
}