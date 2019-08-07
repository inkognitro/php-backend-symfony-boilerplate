<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query\AuditLogEvent\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;
use Ramsey\Uuid\Uuid;

final class EventId implements Attribute
{
    private $id;

    public static function getPayloadKey(): string
    {
        return 'id';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::uuid();
    }

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function create(): self
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function toString(): string
    {
        return $this->id;
    }
}