<?php declare(strict_types=1);

namespace App\Resources\Application\AuditLogEvent\Attributes;

use App\Resources\Application\AttributeTypeId;
use App\Resources\Application\PayloadAttribute;

final class AuthUserPayload extends PayloadAttribute
{
    public static function getPayloadKey(): string
    {
        return 'authUserPayload';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::authUserPayload();
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }
}