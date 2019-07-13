<?php declare(strict_types=1);

namespace App\Resources\Application\AuditLogEvent;

use App\Resources\Application\PayloadAttribute;

final class Payload extends PayloadAttribute
{
    public static function getKey(): string
    {
        return 'auditLogEvent.payload';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}