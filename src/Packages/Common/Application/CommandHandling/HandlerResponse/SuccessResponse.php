<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling\HandlerResponse;

use App\Packages\Common\Application\CommandHandling\Event\EventStream;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\Validation\MessageBag;

final class SuccessResponse implements HandlerResponse
{
    private $events;
    private $warnings;

    public function __construct(EventStream $events, MessageBag $warnings)
    {
        $this->events = $events;
        $this->warnings = $warnings;
    }

    public function getEvents(): EventStream
    {
        return $this->events;
    }

    public function getWarnings(): MessageBag
    {
        return $this->warnings;
    }
}