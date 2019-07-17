<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\JobValidation;

use App\Resources\Application\QueueJob\Attributes\QueueJobId;

interface JobExistsByIdQuery
{
    public function execute(QueueJobId $jobId): bool;
}
