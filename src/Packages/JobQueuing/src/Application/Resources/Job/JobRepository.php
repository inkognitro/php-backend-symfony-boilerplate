<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Resources\Job;

interface JobRepository
{
    public function findById(JobId $jobId): ?Job;
}