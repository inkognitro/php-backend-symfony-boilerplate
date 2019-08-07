<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class UserId implements Attribute
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

    public function isEqual(self $userId): bool
    {
        return (strcasecmp($userId->toString(), $this->toString()) === 0);
    }
}