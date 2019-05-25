<?php declare(strict_types=1);

namespace App\Resources\AuditLogEvent;

use App\Resources\PayloadAttribute;

final class AuthUserPayload extends PayloadAttribute
{
    public static function getKey(): string
    {
        return 'auditLogEvent.authUserPayload';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}