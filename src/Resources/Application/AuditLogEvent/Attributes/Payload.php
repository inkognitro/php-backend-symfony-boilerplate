<?php declare(strict_types=1);

namespace App\Resources\Application\AuditLogEvent\Attributes;

use App\Resources\Application\PayloadAttribute;

final class Payload extends PayloadAttribute
{
    public static function getPayloadKey(): string
    {
        return 'payload';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}