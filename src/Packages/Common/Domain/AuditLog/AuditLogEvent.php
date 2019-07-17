<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\AuditLog;

use App\Resources\Application\AuditLogEvent\Attributes\AuthUserPayload;
use App\Resources\Application\AuditLogEvent\Attributes\EventId;
use App\Resources\Application\AuditLogEvent\Attributes\OccurredAt;
use App\Resources\Application\AuditLogEvent\Attributes\Payload;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceId;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceType;

abstract class AuditLogEvent
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