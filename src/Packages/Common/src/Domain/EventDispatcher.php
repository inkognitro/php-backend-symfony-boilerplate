<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Event\AbstractEvent;
use App\Packages\Common\Domain\Event\EventStream;

final class EventDispatcher
{
    private $auditLogProjection;

    public function __construct(AuditLogProjection $auditLogProjection)
    {
        $this->auditLogProjection = $auditLogProjection;
    }

    public function dispatch(EventStream $events, Projections $projections): void
    {
        foreach($events->toArray() as $event) {
            $this->projectEvent($event, $projections);
        }
    }

    public function projectEvent(AbstractEvent $event, Projections $projections): void
    {
        $this->auditLogProjection->when($event);
        foreach($projections->toArray() as $projection) {
            $projection->when($event);
        }
    }
}