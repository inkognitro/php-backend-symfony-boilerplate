<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Resources\Application\Attribute;

final class VerificationCode implements Attribute
{
    private $code;

    public static function getPayloadKey(): string
    {
        return 'verificationCode';
    }

    private function __construct(?string $code)
    {
        $this->code = $code;
    }

    public static function fromNullableString(?string $code): self
    {
        return new self($code);
    }

    public function toNullableString(): ?string
    {
        return $this->code;
    }

    public function isSame(self $code): bool
    {
        return ($code->toNullableString() === $this->toNullableString());
    }
}