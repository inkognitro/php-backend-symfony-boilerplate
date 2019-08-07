<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Application\ResourceAttributes\Job;

use App\Packages\Common\Application\ResourceAttributes\Attribute;

final class QueueJobId implements Attribute
{
    private $id;

    public static function getPayloadKey(): string
    {
        return 'id';
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