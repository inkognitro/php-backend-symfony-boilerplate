<?php declare(strict_types=1);

namespace App\Packages\Common\Application\JobQueuing;

use App\Packages\Common\Application\Command;
use App\Packages\Common\Domain\JobQueuing\CreateJobHandler;
use App\Utilities\Authentication\AuthUser;
use Ramsey\Uuid\Uuid;

final class CreateJob implements Command
{
    private $jobId;
    private $queueCommand;
    private $executor;

    private function __construct(string $jobId, Command $queueCommand, AuthUser $executor)
    {
        $this->jobId = $jobId;
        $this->queueCommand = $queueCommand;
        $this->executor = $executor;
    }

    public static function create(Command $command, AuthUser $creator): self
    {
        return new self(Uuid::uuid4()->toString(), $command, $creator);
    }

    public function getJobId(): string
    {
        return $this->jobId;
    }

    public function getQueueCommand(): Command
    {
        return $this->queueCommand;
    }

    public static function getHandlerClass(): string
    {
        return CreateJobHandler::class;
    }

    public function getExecutor(): AuthUser
    {
        return $this->executor;
    }
}