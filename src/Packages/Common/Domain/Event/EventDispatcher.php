<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

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

    public function projectEvent(AuditLogEvent $event, Projections $projections): void
    {
        $this->auditLogProjection->when($event);
        foreach($projections->toArray() as $projection) {
            $projection->when($event);
        }
    }
}