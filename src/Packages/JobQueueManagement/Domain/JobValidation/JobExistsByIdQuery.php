<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain\JobValidation;

use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\QueueJobId;

interface JobExistsByIdQuery
{
    public function execute(QueueJobId $jobId): bool;
}
