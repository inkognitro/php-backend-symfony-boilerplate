<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

final class EmailAddress
{
    public const KEY = User::KEY . '.emailAddress';
    private $emailAddress;

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

    public function isEqual(self $emailAddress): bool
    {
        return (strcasecmp($emailAddress->toString(), $this->toString()) === 0);
    }

    public function isSame(self $emailAddress): bool
    {
        return ($emailAddress->toString() === $this->toString());
    }
}