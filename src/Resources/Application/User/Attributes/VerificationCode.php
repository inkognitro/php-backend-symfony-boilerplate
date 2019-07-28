<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Resources\Application\Attribute;
use App\Resources\Application\AttributeTypeId;

final class VerificationCode implements Attribute
{
    private $code;

    public static function getPayloadKey(): string
    {
        return 'verificationCode';
    }

    public static function getTypeId(): AttributeTypeId
    {
        return AttributeTypeId::text();
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