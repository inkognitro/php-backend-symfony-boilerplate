<?php declare(strict_types=1);

namespace App\Resources\Application\AuditLogEvent\Attributes;

use App\Resources\Application\DateTimeAttribute;
use App\Utilities\DateTimeFactory;

final class OccurredAt extends DateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'occurredAt';
    }

    public static function create(): self
    {
        return new self(DateTimeFactory::create());
    }
}