<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Application\Resources\Events\EventStream;

abstract class AbstractAggregate
{
    protected $recordedEvents;

    protected function __construct(EventStream $recordedEvents)
    {
        $this->recordedEvents = $recordedEvents;
    }

    protected function recordEvent(AbstractEvent $event): void
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