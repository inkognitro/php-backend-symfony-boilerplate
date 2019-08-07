<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\AuditLogEvent\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\NullableDateTimeAttribute;
use App\Packages\Common\Utilities\DateTimeFactory;

final class OccurredAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'occurredAt';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::dateTime();
    }

    public static function create(): self
    {
        return new self(DateTimeFactory::createString());
    }
}