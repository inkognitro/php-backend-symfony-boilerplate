<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\NullableDateTimeAttribute;
use App\Packages\Common\Application\Utilities\DateTimeFactory;

final class OccurredAt extends NullableDateTimeAttribute
{
    public static function create(): self
    {
        return new self(DateTimeFactory::create());
    }

    public function toDateTime(): \DateTimeImmutable
    {
        return $this->nullableDateTime;
    }
}