<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Application\Command;

use App\Packages\Common\Application\Command\Command;
use App\Packages\JobQueueManagement\Domain\CreateJobHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser;
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

    public static function getCommandHandlerClass(): string
    {
        return CreateJobHandler::class;
    }

    public function getCommandExecutor(): AuthUser
    {
        return $this->executor;
    }
}