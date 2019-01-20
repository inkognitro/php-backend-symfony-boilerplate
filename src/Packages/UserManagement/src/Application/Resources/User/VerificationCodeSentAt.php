<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Application\Resources\DateTimeValueObject;

final class VerificationCodeSentAt extends DateTimeValueObject
{
    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}