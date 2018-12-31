<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

final class Username
{
    public const NAME = 'username';
    private $username;

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