<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Attributes\Values;

use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Domain\DateTimeValueObject;

final class VerificationCodeSentAt extends DateTimeValueObject
{
    public const KEY = User::KEY . '.verificationCodeSentAt';
    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}