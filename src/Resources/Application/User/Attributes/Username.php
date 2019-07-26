<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Resources\Application\Attribute;

final class Username implements Attribute
{
    private $username;

    public static function getPayloadKey(): string
    {
        return 'username';
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