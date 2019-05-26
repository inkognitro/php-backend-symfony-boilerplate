<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Event\AuditLogEvent;
use App\Packages\Common\Domain\Event\EventStream;

abstract class Aggregate
{
    protected $recordedEvents;

    protected function __construct(EventStream $recordedEvents)
    {
        $this->recordedEvents = $recordedEvents;
    }

    protected function recordEvent(AuditLogEvent $event): void
    {
        $this->recordedEvents = new EventStream(
            array_merge($this->recordedEvents->toArray(), $event)
        );
    }

    public function getRecordedEvents(): EventStream
    {
        return $this->recordedEvents;
    }
}