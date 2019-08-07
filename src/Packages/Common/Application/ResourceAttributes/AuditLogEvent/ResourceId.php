<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\Attribute;

final class ResourceId implements Attribute
{
    private $id;

    public static function getPayloadKey(): string
    {
        return 'resourceId';
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