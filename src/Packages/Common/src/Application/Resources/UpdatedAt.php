<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources;

use App\Packages\Common\Application\DateTimeFactory;

final class UpdatedAt extends DateTimeValueObject
{
    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}