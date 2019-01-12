<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Application\Resources\Events\Event;
use App\Packages\Common\Domain\Event\Projection;

final class AuditLogProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(Event $event): void
    {
        if (!$event->mustBeLogged()) {
            return;
        }

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('event_class');
        $queryBuilder->setValue(
            'previous_payload',
            $queryBuilder->createNamedParameter($event->getPreviousPayload()->toJson())
        );
        $queryBuilder->setValue(
            'payload',
            $queryBuilder->createNamedParameter($event->getPayload()->toJson())
        );
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
            'auth_user_language_id',
            $queryBuilder->createNamedParameter($event->getOccurredOn(), 'datetime')
        );
    }
}