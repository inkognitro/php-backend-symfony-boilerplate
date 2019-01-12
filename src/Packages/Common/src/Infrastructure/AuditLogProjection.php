<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Domain\Event\Projection;

final class AuditLogProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(AbstractEvent $event): void
    {
        if (!$event->mustBeLogged()) {
            return;
        }

        $resource = $event->getResource();
        $resourceClassName = ($resource !== null ? get_class($resource) : null);
        $resourceId = ($resource !== null ? $resource->getResourceId()->toString() : null);
        $previousPayload = ($event->getPreviousPayload() === null ? null : $event->getPreviousPayload()->toJson());

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('audit_log');
        $queryBuilder->setValue('id', $queryBuilder->createNamedParameter($event->getId()->toString()));
        $queryBuilder->setValue('event_class_name', $queryBuilder->createNamedParameter(get_class($event)));
        $queryBuilder->setValue('resource_class_name', $queryBuilder->createNamedParameter($resourceClassName));
        $queryBuilder->setValue('resource_id', $queryBuilder->createNamedParameter($resourceId));
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
            'occurred_on',
            $queryBuilder->createNamedParameter($event->getOccurredOn()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}