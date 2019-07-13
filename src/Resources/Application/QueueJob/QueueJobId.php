<?php declare(strict_types=1);

namespace App\Resources\Application\QueueJob;

use App\Resources\Application\Attribute;

final class QueueJobId implements Attribute
{
    private $id;

    public static function getKey(): string
    {
        return 'queueJob.id';
    }

    private function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function fromString(string $id): self
    {
        return new self($id);
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function isEqual(self $jobId): bool
    {
        return (strcasecmp($jobId->toString(), $this->toString()) === 0);
    }
}