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
    public function toCollection(): array
    {
        return $this->events;
    }
}