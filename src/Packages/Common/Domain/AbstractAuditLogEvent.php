<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Resources\AuditLogEvent\AuditLogEvent;
use App\Resources\AuditLogEvent\AuthUserPayload;
use App\Resources\AuditLogEvent\EventId;
use App\Resources\AuditLogEvent\OccurredAt;
use App\Resources\AuditLogEvent\Payload;
use App\Resources\AuditLogEvent\ResourceId;

abstract class AbstractAuditLogEvent implements AuditLogEvent
{
    private $id;
    private $resourceId;
    private $payload;
    private $authUserPayload;
    private $occurredAt;

    public abstract function mustBeLogged(): bool;
    public abstract static function getResourceType(): string;

    protected function __construct(
        EventId $id,
        ResourceId $resourceId,
        Payload $payload,
        AuthUserPayload $authUserPayload,
        OccurredAt $occurredAt
    )
    {
        $this->id = $id;
        $this->resourceId = $resourceId;
        $this->payload = $payload;
        $this->authUserPayload = $authUserPayload;
        $this->occurredAt = $occurredAt;
    }

    public function getId(): EventId
    {
        return $this->id;
    }

    public function getResourceId(): ResourceId
    {
        return $this->resourceId;
    }

    public function getPayload(): Payload
    {
        return $this->payload;
    }

    public function getAuthUserPayload(): AuthUserPayload
    {
        return $this->authUserPayload;
    }

    public function getOccurredAt(): OccurredAt
    {
        return $this->occurredAt;
    }
}