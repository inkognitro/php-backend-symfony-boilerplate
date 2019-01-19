<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Infrastructure\Job;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Domain\Event\Projection;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\JobQueuing\Application\Resources\Job\Events\JobWasCreated;

final class DbalJobProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(AbstractEvent $event): void
    {
        if ($event instanceof JobWasCreated) {
            $this->projectJobWasCreated($event);
        }
    }

    private function projectJobWasCreated(JobWasCreated $event): void
    {
        $job = $event->getJob();

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('jobs');
        $queryBuilder->setValue(
            'id', $queryBuilder->createNamedParameter($job->getId()->toString())
        );
        $queryBuilder->setValue(
            'command', $queryBuilder->createNamedParameter(json_encode($job->getCommand()))
        );
        $queryBuilder->setValue(
            'created_at', $queryBuilder->createNamedParameter($job->getCreatedAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}