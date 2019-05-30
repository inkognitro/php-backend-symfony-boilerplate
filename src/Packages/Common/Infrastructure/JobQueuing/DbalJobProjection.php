<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\JobQueuing;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Domain\JobQueuing\Events\JobWasCreated;
use App\Packages\Common\Domain\JobQueuing\JobProjection;
use App\Packages\Common\Infrastructure\DbalConnection;
use LogicException;

final class DbalJobProjection implements JobProjection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AuditLogEvent $event): void
    {
        if ($event instanceof JobWasCreated) {
            $this->whenJobWasCreated($event);
        }
        throw new LogicException('event "' . get_class($event) . '" is not supported');
    }

    private function whenJobWasCreated(JobWasCreated $event): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('jobs');
        $queryBuilder->setValue(
            'id', $queryBuilder->createNamedParameter($event->getJobId()->toString())
        );
        $queryBuilder->setValue(
            'command', $queryBuilder->createNamedParameter($event->getCommand()->toSerializedString())
        );
        $queryBuilder->setValue(
            'created_at', $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}