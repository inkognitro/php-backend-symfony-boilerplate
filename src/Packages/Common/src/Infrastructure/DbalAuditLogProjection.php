<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Domain\AuditLogProjection;
use App\Packages\Common\Domain\Event\AbstractEvent;

final class DbalAuditLogProjection implements AuditLogProjection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AbstractEvent $event): void
    {
        if (!$event->mustBeLogged()) {
            return;
        }

        $aggregateId = $event->getAggregateId();
        $aggregateType = $event->getAggregateType();
        $previousPayload = ($event->getPreviousPayload() === null ? null : $event->getPreviousPayload()->toJson());

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('audit_log');
        $queryBuilder->setValue('id', $queryBuilder->createNamedParameter($event->getId()->toString()));
        $queryBuilder->setValue('event', $queryBuilder->createNamedParameter(get_class($event)));
        $queryBuilder->setValue('aggregate_type', $queryBuilder->createNamedParameter($aggregateType));
        $queryBuilder->setValue('aggregate_id', $queryBuilder->createNamedParameter($aggregateId));
        $queryBuilder->setValue('previous_payload', $queryBuilder->createNamedParameter($previousPayload));
        $queryBuilder->setValue('payload', $queryBuilder->createNamedParameter($event->getPayload()->toJson()));
        $queryBuilder->setValue(
            'auth_user_role',
            $queryBuilder->createNamedParameter($event->getTriggeredFrom()->getRole())
        );
        $queryBuilder->setValue(
            'auth_user_id',
            $queryBuilder->createNamedParameter($event->getTriggeredFrom()->getUserId())
        );
        $queryBuilder->setValue(
            'auth_user_language_id',
            $queryBuilder->createNamedParameter($event->getTriggeredFrom()->getLanguageId())
        );
        $queryBuilder->setValue(
            'occurred_at',
            $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}