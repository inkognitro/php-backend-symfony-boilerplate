<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job;

use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\Common\Domain\Projections;

final class JobRepository
{
    private $eventDispatcher;
    private $jobProjection;
    private $jobsQueryHandler;

    public function __construct(
        EventDispatcher $eventDispatcher,
        JobProjection $jobProjection,
        JobsQueryHandler $jobsQueryHandler
    ){
        $this->eventDispatcher = $eventDispatcher;
        $this->jobProjection = $jobProjection;
        $this->jobsQueryHandler = $jobsQueryHandler;
    }

    public function findByQuery(JobQuery $query): ?Job
    {
        $this->jobsQueryHandler->handle();
    }

    public function save(JobAggregate $job): void
    {
        $this->eventDispatcher->dispatch(
            $job->getRecordedEvents(),
            Projections::fromArray([
                $this->jobProjection,
            ])
        );
    }
}