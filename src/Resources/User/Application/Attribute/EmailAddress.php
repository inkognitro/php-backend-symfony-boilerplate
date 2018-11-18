<?php declare(strict_types=1);

namespace App\Resources\User\Application\Attribute;

final class EmailAddress
{
    public const NAME = 'emailAddress';
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

    public function equals(self $emailAddress): bool
    {
        return (strcasecmp($emailAddress->toString(), $this->toString()) === 0);
    }
}