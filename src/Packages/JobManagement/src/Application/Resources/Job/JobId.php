<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Resources\Job;

use App\Packages\Common\Application\Resources\ResourceId;

final class JobId implements ResourceId
{
    private $id;

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