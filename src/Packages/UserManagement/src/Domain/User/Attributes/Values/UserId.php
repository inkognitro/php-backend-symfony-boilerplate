<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Attributes\Values;

use App\Packages\Common\Domain\Events\AggregateId;

final class UserId
{
    public const KEY = User::KEY . '.id';
    private $id;

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function toAggregateId(): AggregateId
    {
        return AggregateId::fromString($this->id);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function isEqual(self $userId): bool
    {
        return (strcasecmp($userId->toString(), $this->toString()) === 0);
    }
}