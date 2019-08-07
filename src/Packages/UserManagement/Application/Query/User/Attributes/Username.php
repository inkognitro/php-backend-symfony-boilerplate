<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class Username implements Attribute
{
    private $username;

    public static function getPayloadKey(): string
    {
        return 'username';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::text();
    }

    private function __construct(string $username)
    {
        $this->username = $username;
    }

    public static function fromString(string $username): self
    {
        return new self($username);
    }

    public function toString(): string
    {
        return $this->username;
    }

    public function isEqual(self $username): bool
    {
        return (strcasecmp($username->toString(), $this->toString()) === 0);
    }

    public function isSame(self $username): bool
    {
        return ($username->toString() === $this->toString());
    }
}