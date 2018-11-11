<?php declare(strict_types=1);

namespace App\Resources\Common\Application\Command;

use App\Packages\Common\Application\CommandHandling\Event\Event;
use App\Packages\Common\Application\CommandHandling\Event\EventStream;

abstract class AbstractCommandResource implements CommandResource
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