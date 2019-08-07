<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\AuditLogEvent\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\PayloadAttribute;

final class Payload extends PayloadAttribute
{
    public static function getPayloadKey(): string
    {
        return 'payload';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::dateTime();
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}