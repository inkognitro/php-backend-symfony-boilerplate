<?php declare(strict_types=1);

namespace App\Resources\Application\QueueJob\Attributes;

use App\Resources\Application\AttributeTypeId;
use App\Resources\Application\DateTimeAttribute;
use App\Utilities\DateTimeFactory;

final class ExecutedAt extends DateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'executedAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::dateTime();
    }
}