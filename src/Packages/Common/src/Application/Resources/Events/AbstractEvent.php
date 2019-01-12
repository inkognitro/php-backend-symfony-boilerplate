<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\AbstractResource;

abstract class AbstractEvent
{
    private $id;
    private $occurredOn;
    private $triggeredFrom;
    private $payload;
    private $previousPayload;

    protected function __construct(
        EventId $id,
        OccurredOn $occurredOn,
        AuthUser $triggeredFrom,
        AbstractPayload $payload,
        ?AbstractPayload $previousPayload
    )
    {
        $this->id = $id;
        $this->occurredOn = $occurredOn;
        $this->triggeredFrom = $triggeredFrom;
        $this->payload = $payload;
        $this->previousPayload = $previousPayload;
    }

    public abstract function mustBeLogged(): bool;
    public abstract function getResource(): ?AbstractResource;

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getOccurredOn(): OccurredOn
    {
        return $this->occurredOn;
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