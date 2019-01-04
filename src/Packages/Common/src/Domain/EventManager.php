<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Event\EventStream;

abstract class EventManager
{
    protected $recordedEvents;

    protected function __construct(EventStream $recordedEvents)
    {
        $this->recordedEvents = new EventStream([]);
    }

    protected function recordEvents(EventStream $events): void
    {
        $this->recordedEvents = $this->recordedEvents->getMerged($events);
    }

    public function getRecordedEvents(): EventStream
    {
        return new $this->recordedEvents;
    }
}