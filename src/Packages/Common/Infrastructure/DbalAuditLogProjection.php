<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Packages\Common\Domain\Event\AuditLogProjection;

final class DbalAuditLogProjection implements AuditLogProjection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AuditLogEvent $event): void
    {
        if (!$event->mustBeLogged()) {
            return;
        }
        $resourceId = $event->getResourceId();
        $resourceType = $event::getResourceType();
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('audit_log');
        $queryBuilder->setValue('id', $queryBuilder->createNamedParameter($event->getId()->toString()));
        $queryBuilder->setValue('event', $queryBuilder->createNamedParameter(get_class($event)));
        $queryBuilder->setValue('resource_type', $queryBuilder->createNamedParameter($resourceType));
        $queryBuilder->setValue('resource_id', $queryBuilder->createNamedParameter($resourceId));
        $queryBuilder->setValue('payload', $queryBuilder->createNamedParameter($event->getPayload()->toJson()));
        $queryBuilder->setValue('auth_user_payload', $queryBuilder->createNamedParameter($event->getPayload()->toJson()));
        $queryBuilder->setValue(
            'occurred_at',
            $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}