<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\NullableDateTimeAttribute;

final class VerificationCodeSentAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'verificationCodeSentAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self($dateTime);
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::dateTime();
    }
}