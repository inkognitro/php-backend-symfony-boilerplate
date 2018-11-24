<?php declare(strict_types=1);

namespace App\Resources\User\Application\Attribute;

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

    public function isEqualTo(self $username): bool
    {
        return ($username->toString() === $this->toString());
    }
}