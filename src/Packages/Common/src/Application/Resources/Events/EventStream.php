<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

final class EventStream
{
    private $events;

    /** @param $events AbstractEvent[] */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /** @return AbstractEvent[] */
    public function toArray(): array
    {
        return $this->events;
    }

    public function record(AbstractEvent $event):void
    {
        $this->events[] = $event;
    }
}