<?php declare(strict_types=1);

namespace App\Resources\User;

use App\Resources\DateTimeAttribute;
use App\Utilities\DateTimeFactory;

final class VerifiedAt extends DateTimeAttribute
{
    public static function getKey(): string
    {
        return 'user.verfiedAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}