<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Command\CreateJob;

use App\Packages\Common\Application\Command\Command;

final class CreateJob implements Command
{
    private $jobId;

    private function __construct(string $jobId)
    {
        $this->jobId = $jobId;
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }
}