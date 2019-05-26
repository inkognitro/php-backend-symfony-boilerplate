<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\JobValidation;

use App\Resources\QueueJob\QueueJobId;

interface JobExistsByIdQuery
{
    public function execute(QueueJobId $jobId): bool;
}
