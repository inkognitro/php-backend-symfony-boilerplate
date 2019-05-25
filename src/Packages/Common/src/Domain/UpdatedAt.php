<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\DateTimeFactory;

final class UpdatedAt extends DateTimeValueObject
{
    public const KEY = 'updatedAt';

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}