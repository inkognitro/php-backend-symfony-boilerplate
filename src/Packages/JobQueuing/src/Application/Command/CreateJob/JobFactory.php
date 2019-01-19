<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Command\CreateJob;

use App\Packages\JobQueuing\Application\Resources\Job\Job;
use App\Packages\JobQueuing\Application\Resources\Job\JobId;

final class JobFactory
{
    public function create(CreateJob $command): Job
    {
        $createdAt = null;
        $updatedAt = null;
        return new Job(
            JobId::fromString($command->getJobId()),
            $createdAt,
            $updatedAt
        );
    }
}