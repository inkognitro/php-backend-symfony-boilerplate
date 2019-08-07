<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query\AuthUser\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class LanguageId implements Attribute
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
        return strtolower($this->id);
    }

    public static function english(): self
    {
        return new self('en');
    }

    public static function german(): self
    {
        return new self('de');
    }

    public function equals(self $that): bool
    {
        return (strcasecmp($this->toString(), $that->toString()) === 0);
    }
}