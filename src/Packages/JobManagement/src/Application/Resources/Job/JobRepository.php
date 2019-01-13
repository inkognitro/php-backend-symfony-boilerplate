<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Resources\Job;

interface JobRepository
{
    public function findById(JobId $jobId): ?Job;
}