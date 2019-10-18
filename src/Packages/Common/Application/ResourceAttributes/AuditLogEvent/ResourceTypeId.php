<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\Attribute;

final class ResourceTypeId implements Attribute
{
    private $id;

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

    public static function queueJob(): self
    {
        return new self('queueJob');
    }

    public static function user(): self
    {
        return new self('user');
    }

    public function equals(self $that): bool
    {
        return ($this->toString() === $that->toString());
    }
}