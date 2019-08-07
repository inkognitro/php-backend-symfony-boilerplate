<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\NullableDateTimeAttribute;
use App\Packages\Common\Utilities\DateTimeFactory;

final class CreatedAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'createdAt';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::dateTime();
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}