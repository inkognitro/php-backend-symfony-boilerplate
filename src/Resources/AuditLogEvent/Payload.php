<?php declare(strict_types=1);

namespace App\Resources\AuditLogEvent;

use App\Resources\Attribute;

final class Payload implements Attribute
{
    protected $data;

    public static function getKey(): string
    {
        return 'auditLogEvent.payload';
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}