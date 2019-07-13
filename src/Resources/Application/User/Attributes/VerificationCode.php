<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Resources\Application\Attribute;

final class VerificationCode implements Attribute
{
    private $code;

    public static function getKey(): string
    {
        return 'user.id';
    }

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