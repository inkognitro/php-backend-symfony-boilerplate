<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\AuditLog;

use App\Resources\Application\AuditLogEvent\AuditLogEvent as AuditLogEventResource;
use App\Resources\Application\AuditLogEvent\AuthUserPayload;
use App\Resources\Application\AuditLogEvent\EventId;
use App\Resources\Application\AuditLogEvent\OccurredAt;
use App\Resources\Application\AuditLogEvent\Payload;
use App\Resources\Application\AuditLogEvent\ResourceId;
use App\Resources\Application\AuditLogEvent\ResourceType;

abstract class AuditLogEvent implements AuditLogEventResource
{
    private $id;
    private $resourceId;
    private $payload;
    private $authUserPayload;
    private $occurredAt;

    public abstract function mustBeLogged(): bool;
    public abstract static function getResourceType(): ResourceType;

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