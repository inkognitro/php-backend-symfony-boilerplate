<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\AuditLogEvent\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class ResourceId implements Attribute
{
    private $id;

    public static function getPayloadKey(): string
    {
        return 'resourceId';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::uuid();
    }

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }
}