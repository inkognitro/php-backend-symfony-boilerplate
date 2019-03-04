<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

final class VerificationCode
{
    public const KEY = User::KEY . '.verificationCode';
    private $code;

    private function __construct(string $code)
    {
        $this->code = $code;
    }

    public static function fromString(string $code): self
    {
        return new self($code);
    }

    public function toString(): string
    {
        return $this->code;
    }

    public function isSame(self $code): bool
    {
        return ($code->toString() === $this->toString());
    }
}