<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\PayloadAttribute;

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