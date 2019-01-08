<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Domain\Event\Event;
use App\Packages\Common\Domain\Event\EventStream;

abstract class AbstractAggregate
{
    protected $recordedEvents;

    protected function __construct(EventStream $recordedEvents)
    {
        $this->recordedEvents = $recordedEvents;
    }

    protected function recordEvent(Event $event): void
    {
        $this->recordedEvents = new EventStream(
            array_merge($this->recordedEvents->toCollection(), $event)
        );
    }

    public function getRecordedEvents(): EventStream
    {
        return $this->recordedEvents;
    }
}