<?php declare(strict_types=1);

namespace App\Resources\User;

use App\Utilities\DateTimeFactory;
use App\Resources\DateTimeAttribute;

final class VerificationCodeSentAt extends DateTimeAttribute
{
    public static function getKey(): string
    {
        return 'user.verificationCodeSentAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}