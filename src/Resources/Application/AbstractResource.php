<?php declare(strict_types=1);

namespace App\Resources\Application;

use App\Packages\Common\Application\CommandHandling\Event\Event;
use App\Packages\Common\Application\CommandHandling\Event\EventStream;

abstract class AbstractResource implements Resource
{
    protected $recordedEvents;
    protected $persistedData;

    protected function __construct(EventStream $recordedEvents, ?array $persistedData)
    {
        $this->recordedEvents = new EventStream([]);
        $this->persistedData = $persistedData;
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