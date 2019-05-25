<?php declare(strict_types=1);

namespace App\Resources\AuditLogEvent\Attributes;

use App\Resources\Attribute;
use Ramsey\Uuid\Uuid;

final class EventId implements Attribute
{
    private $id;

    public static function getKey(): string
    {
        return 'auditLogEvent.id';
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