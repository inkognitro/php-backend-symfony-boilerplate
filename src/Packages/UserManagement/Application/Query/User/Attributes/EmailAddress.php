<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\Attribute;
use App\Packages\Common\Application\Query\AttributeTypeId;

final class EmailAddress implements Attribute
{
    private $emailAddress;

    public static function getPayloadKey(): string
    {
        return 'emailAddress';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::emailAddress();
    }

    private function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public static function fromString(string $emailAddress): self
    {
        return new self($emailAddress);
    }

    public function toString(): string
    {
        return $this->emailAddress;
    }

    public function isEqual(self $emailAddress): bool //todo check method for all attributes if used or not!
    {
        return (strcasecmp($emailAddress->toString(), $this->toString()) === 0);
    }

    public function isSame(self $emailAddress): bool //todo check method for all attributes if used or not!
    {
        return ($emailAddress->toString() === $this->toString());
    }
}