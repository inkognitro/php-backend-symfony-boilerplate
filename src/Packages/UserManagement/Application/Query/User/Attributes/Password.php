<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User\Attributes;

use App\Packages\Common\Application\Query\AttributeTypeId;
use App\Packages\Common\Application\Query\Attribute;

final class Password implements Attribute
{
    private $passwordHash;

    public static function getPayloadKey(): string
    {
        return 'passwordHash';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::text();
    }

    private function __construct(string $passwordHash)
    {
        $this->passwordHash = $passwordHash;
    }

    public static function fromString(string $password): self
    {
        $algorithm = PASSWORD_BCRYPT;
        $algorithmOptions = ['cost' => 8];
        $hash = password_hash($password, $algorithm, $algorithmOptions);
        return new self($hash);
    }

    public static function fromHash(string $passwordHash): self
    {
        return new self($passwordHash);
    }

    public function toHash(): string
    {
        return $this->passwordHash;
    }

    public function isSame(self $that): bool
    {
        return hash_equals($this->toHash(), $that->toHash());
    }
}