<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;

final class JobsQuery
{
    private $jobId;

    public function __construct(JobId $jobId)
    {
        $this->jobId = $jobId;
    }

    public function getMustBeInJobIds(): ?JobId
    {
        return $this->jobId;
    }

    public function addOr(string $attributeId, $value, ?string $groupId = null): void
    {

    }
}