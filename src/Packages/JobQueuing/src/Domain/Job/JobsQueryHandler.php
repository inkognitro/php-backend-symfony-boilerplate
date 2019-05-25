<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

interface JobsQueryHandler
{
    public function handle(JobsQuery $jobsQuery): Jobs;
}