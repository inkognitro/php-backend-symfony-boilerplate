<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\DateTimeFactory;
use App\Packages\Common\Domain\DateTimeValueObject;

final class CreatedAt extends DateTimeValueObject
{
    public const KEY = 'createdAt';

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}