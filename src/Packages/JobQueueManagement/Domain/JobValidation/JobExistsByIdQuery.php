<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain\JobValidation;

use App\Packages\Common\Application\Query\QueueJob\Attributes\QueueJobId;

interface JobExistsByIdQuery
{
    public function execute(QueueJobId $jobId): bool;
}
