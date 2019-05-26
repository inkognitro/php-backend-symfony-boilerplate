<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Commands\CreateJob;

use App\Packages\Common\Application\Command;
use App\Utilities\AuthUser;
use Ramsey\Uuid\Uuid;

final class CreateJob implements Command
{
    private $jobId;
    private $command;
    private $executor;

    private function __construct(string $jobId, Command $command, AuthUser $executor)
    {
        $this->jobId = $jobId;
        $this->command = $command;
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

    public function getCommand(): Command
    {
        return $this->command;
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