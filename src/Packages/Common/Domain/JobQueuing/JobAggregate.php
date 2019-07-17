<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Packages\Common\Domain\JobQueuing\Events\JobWasCreated;
use App\Resources\Application\QueueJob\Attributes\Attributes\Command;
use App\Resources\Application\QueueJob\QueueJob;
use App\Resources\Application\QueueJob\Attributes\QueueJobId;
use App\Utilities\Authentication\AuthUser;

final class JobAggregate extends Aggregate implements QueueJob
{
    protected function __construct(EventStream $recordedEvents)
    {
        parent::__construct($recordedEvents);
    }

    public static function create(
        QueueJobId $jobId,
        Command $command,
        AuthUser $creator
    ): self
    {
        $persistedUser = null;
        $events = [
            JobWasCreated::occur($jobId, $command, $creator),
        ];
        return new self(new EventStream($events));
    }
}