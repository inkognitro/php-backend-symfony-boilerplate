<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;

abstract class AbstractEvent
{
    private $id;
    private $occurredAt;
    private $triggeredFrom;
    private $payload;
    private $previousPayload;

    public abstract function mustBeLogged(): bool;
    public abstract function getAggregateId(): AggregateId;
    public static abstract function getAggregateType(): string;

    protected function __construct(
        EventId $id,
        OccurredAt $occurredAt,
        AuthUser $triggeredFrom,
        AbstractPayload $payload,
        ?AbstractPayload $previousPayload
    )
    {
        $this->id = $id;
        $this->occurredAt = $occurredAt;
        $this->triggeredFrom = $triggeredFrom;
        $this->payload = $payload;
        $this->previousPayload = $previousPayload;
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getOccurredAt(): OccurredAt
    {
        return $this->occurredAt;
    }

    public function getTriggeredFrom(): AuthUser
    {
        return $this->triggeredFrom;
    }

    public function getPayload(): AbstractPayload
    {
        return $this->payload;
    }

    public function getPreviousPayload(): ?AbstractPayload
    {
        return $this->previousPayload;
    }
}