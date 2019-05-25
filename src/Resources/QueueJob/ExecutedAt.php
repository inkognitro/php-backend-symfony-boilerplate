<?php declare(strict_types=1);

namespace App\Resources\QueueJob;

use App\Resources\DateTimeAttribute;
use App\Utilities\DateTimeFactory;

final class ExecutedAt extends DateTimeAttribute
{
    public static function getKey(): string
    {
        return 'queueJob.executedAt';
    }

    public static function fromString(string $dateTime): self
    {
        return new self(DateTimeFactory::createFromString($dateTime));
    }
}