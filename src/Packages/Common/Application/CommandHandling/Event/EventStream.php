<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling\Event;

final class EventStream
{
    private $events;

    /** @param $events Event[] */
    public function __construct(array $events)
    {
        $this->events;
    }

    /** @return Event[] */
    public function toCollection(): array
    {
        return $this->events;
    }
}