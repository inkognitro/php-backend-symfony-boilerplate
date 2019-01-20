<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\Events\EventStream;
use App\Packages\Common\Domain\AbstractAggregate;
use App\Packages\JobQueuing\Application\Resources\Events\JobWasCreated;
use App\Packages\JobQueuing\Application\Resources\Job\Job;

final class JobAggregate extends AbstractAggregate
{
    private $persistedJob;
    private $currentJob;

    protected function __construct(EventStream $recordedEvents, ?Job $persistedJob, Job $currentJob)
    {
        parent::__construct($recordedEvents);
        $this->currentJob = $currentJob;
        $this->persistedJob = $persistedJob;
    }

    public function getJob(): Job
    {
        return $this->currentJob;
    }

    public static function fromNewJob(Job $job, AuthUser $creator): self
    {
        $persistedJob = null;
        $event = JobWasCreated::occur($job, $creator);
        $createdJob = $event->getJob();
        return new self(new EventStream([$event]), $persistedJob, $createdJob);
    }
}