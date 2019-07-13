<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\JobValidation;

use App\Resources\Application\QueueJob\QueueJobId;

interface JobExistsByIdQuery
{
    public function execute(QueueJobId $jobId): bool;
}
