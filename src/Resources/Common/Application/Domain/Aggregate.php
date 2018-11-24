<?php declare(strict_types=1);

namespace App\Resources\Common\Application\Domain;

use App\Packages\Common\Application\Domain\Event\Event;
use App\Packages\Common\Application\Domain\Event\EventStream;

abstract class Aggregate
{
    protected $recordedEvents;

    protected function __construct(EventStream $recordedEvents)
    {
        $this->recordedEvents = new EventStream([]);
    }

    protected function recordEvent(Event $event): void
    {
        $this->recordedEvents = new EventStream(
            array_merge($this->recordedEvents->toCollection(), $event)
        );
    }

    public function getRecordedEvents(): EventStream
    {
        return new $this->recordedEvents;
    }
}