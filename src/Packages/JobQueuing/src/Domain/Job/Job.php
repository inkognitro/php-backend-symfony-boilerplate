<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\Common\Application\Command\Command;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\Job\Attributes\Values\ExecutedAt;
use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;

final class Job
{
    private $id;
    private $command;
    private $createdAt;
    private $executedAt;

    public function __construct(
        JobId $id,
        Command $command,
        ?CreatedAt $createdAt,
        ?ExecutedAt $executedAt
    ) {
        $this->id = $id;
        $this->command = $command;
        $this->createdAt = $createdAt;
        $this->executedAt = $executedAt;
    }

    public function getId(): JobId
    {
        return $this->id;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }

    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    public function getExecutedAt(): ?ExecutedAt
    {
        return $this->executedAt;
    }
}