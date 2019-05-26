<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Event;

final class EventStream
{
    private $events;

    /** @param $events AuditLogEvent[] */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /** @return AuditLogEvent[] */
    public function toArray(): array
    {
        return $this->events;
    }
}