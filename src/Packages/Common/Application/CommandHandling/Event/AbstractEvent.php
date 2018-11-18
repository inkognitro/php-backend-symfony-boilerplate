<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling\Event;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use DateTimeImmutable;

abstract class AbstractEvent implements Event
{
    private $occurredOn;
    private $triggeredFrom;
    private $payload;
    private $previousPayload;

    protected function __construct(
        DateTimeImmutable $occurredOn,
        AuthUser $triggeredFrom,
        Payload $payload,
        ?Payload $previousPayload
    )
    {
        $this->occurredOn = $occurredOn;
        $this->triggeredFrom = $triggeredFrom;
        $this->payload = $payload;
        $this->previousPayload = $previousPayload;
    }

    public function getOccurredOn(): DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getTriggeredFrom(): AuthUser
    {
        return $this->triggeredFrom;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function getPreviousPayload(): ?Payload
    {
        return $this->previousPayload;
    }

    public abstract function getResourceClass(): string;
}