<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\ResourceAttributes\User;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;

final class VerificationCodeSentAt extends NullableDateTimeAttribute
{
    public static function fromString(string $dateTime): self
    {
        /** @var $valueObject VerificationCodeSentAt */
        $valueObject = self::fromInternalNullableString($dateTime);
        return $valueObject;
    }
}