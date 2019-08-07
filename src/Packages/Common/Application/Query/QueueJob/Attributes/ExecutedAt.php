<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\QueueJob\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\NullableDateTimeAttribute;

final class ExecutedAt extends NullableDateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'executedAt';
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