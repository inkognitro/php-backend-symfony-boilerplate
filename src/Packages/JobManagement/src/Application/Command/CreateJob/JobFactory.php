<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Command\CreateJob;

use App\Packages\JobManagement\Application\Resources\Job\Job;
use App\Packages\JobManagement\Application\Resources\Job\JobId;

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