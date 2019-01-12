<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;

abstract class AbstractEvent implements Event
{
    private $occurredOn;
    private $triggeredFrom;
    private $payload;
    private $previousPayload;

    protected function __construct(
        OccurredOn $occurredOn,
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

    public function getOccurredOn(): OccurredOn
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
}