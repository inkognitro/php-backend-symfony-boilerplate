<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\AuditLogEvent\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\PayloadAttribute;

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