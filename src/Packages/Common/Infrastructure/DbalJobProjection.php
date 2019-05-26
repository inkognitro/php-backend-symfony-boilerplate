<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Packages\Common\Domain\Event\Projection;
use App\Packages\Common\Domain\JobQueuing\Events\JobWasCreated;
use LogicException;

final class DbalJobProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AuditLogEvent $event): void
    {
        if ($event instanceof JobWasCreated) {
            $this->projectJobWasCreated($event);
        }
        throw new LogicException('event "' . get_class($event) . '" is not supported');
    }

    private function projectJobWasCreated(JobWasCreated $event): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('jobs');
        $queryBuilder->setValue(
            'id', $queryBuilder->createNamedParameter($event->getJobId()->toString())
        );
        $queryBuilder->setValue(
            'command', $queryBuilder->createNamedParameter($event->getCommand()->toSerialized())
        );
        $queryBuilder->setValue(
            'created_at', $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}