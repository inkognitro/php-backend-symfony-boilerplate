<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Command\CreateJob;

use App\Packages\Common\Application\Command\Command;
use Ramsey\Uuid\Uuid;

final class CreateJob implements Command
{
    private $jobId;
    private $command;

    private function __construct(string $jobId, Command $command)
    {
        $this->jobId = $jobId;
        $this->command = $command;
    }

    public static function create(Command $command): self
    {
        return new self(Uuid::uuid4()->toString(), $command);
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
}