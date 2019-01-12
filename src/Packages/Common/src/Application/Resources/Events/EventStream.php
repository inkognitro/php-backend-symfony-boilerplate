<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

use App\Packages\Common\Application\Resources\Events\Event;

final class EventStream
{
    private $events;

    /** @param $events Event[] */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    /** @return Event[] */
    public function toCollection(): array
    {
        return $this->events;
    }

    public function getMerged(self $eventStream): self
    {
        $events = array_merge($this->events, $eventStream->toCollection());
        usort($events, function (Event $a, Event $b) {
            $comparisonFormat = 'YmdHis';
            if ($a->getOccurredOn()->format($comparisonFormat) === $b->getOccurredOn()->format($comparisonFormat)) {
                return 0;
            }
            return ($a->getOccurredOn() < $b->getOccurredOn() ? -1 : 1);
        });
        return new self($events);
    }
}