<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing;

use App\Packages\Common\Domain\AuditLog\EventDispatcher;
use App\Packages\Common\Domain\AuditLog\Projections;

final class JobRepository
{
    private $eventDispatcher;
    private $jobProjection;

    public function __construct(EventDispatcher $eventDispatcher, JobProjection $jobProjection)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->jobProjection = $jobProjection;
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