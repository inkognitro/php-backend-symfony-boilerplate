<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\AuditLog\EventStream;
use App\Packages\JobQueueManagement\Domain\Events\JobWasCreated;
use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\Attributes\Command;
use App\Packages\Common\Application\Query\QueueJob\QueueJob;
use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\QueueJobId;
use App\Packages\AccessManagement\Application\Query\AuthUser;

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