<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Resources\Application\Attribute;

final class UserId implements Attribute
{
    private $id;

    public static function getPayloadKey(): string
    {
       return 'id';
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