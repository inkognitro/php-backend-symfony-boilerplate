<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing;

use App\Packages\Common\Domain\Aggregate;
use App\Packages\Common\Domain\Event\EventStream;
use App\Packages\Common\Domain\JobQueuing\Events\JobWasCreated;
use App\Resources\QueueJob\Command;
use App\Resources\QueueJob\QueueJob;
use App\Resources\QueueJob\QueueJobId;
use App\Utilities\AuthUser;

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