<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;

final class Jobs
{
    private $jobs;

    /** @param $jobs Job[] */
    public function __construct(array $jobs)
    {
        $this->jobs = $jobs;
    }

    public function findById(JobId $jobId): ?Job
    {
        foreach ($this->jobs as $job) {
            if ($job->getId()->isEqual($jobId)) {
                return $job;
            }
        }
        return null;
    }
}