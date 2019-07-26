<?php declare(strict_types=1);

namespace App\Resources\Application\User\Attributes;

use App\Utilities\DateTimeFactory;
use App\Resources\Application\DateTimeAttribute;

final class VerificationCodeSentAt extends DateTimeAttribute
{
    public static function getPayloadKey(): string
    {
        return 'verificationCodeSentAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}