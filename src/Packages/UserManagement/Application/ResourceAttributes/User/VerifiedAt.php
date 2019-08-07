<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\ResourceAttributes\User;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;

final class VerifiedAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'verifiedAt';
    }

    public static function fromString(string $dateTime): self
    {
        /** @var $valueObject VerifiedAt */
        $valueObject = self::fromInternalNullableString($dateTime);
        return $valueObject;
    }
}