<?php declare(strict_types=1);

namespace App\Packages\Common\Domain\JobQueuing\Events;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceType;
use App\Resources\Application\QueueJob\Attributes\Attributes\Command;
use App\Resources\Application\AuditLogEvent\Attributes\EventId;
use App\Resources\Application\AuditLogEvent\Attributes\OccurredAt;
use App\Resources\Application\AuditLogEvent\Attributes\Payload;
use App\Resources\Application\AuditLogEvent\Attributes\ResourceId;
use App\Resources\Application\QueueJob\QueueJob;
use App\Resources\Application\QueueJob\Attributes\QueueJobId;
use App\Utilities\Authentication\AuthUser;

final class JobWasCreated extends AuditLogEvent
{
    public static function occur(QueueJobId $jobId, Command $command, AuthUser $creator): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $resourceId = ResourceId::fromString($jobId->toString());
        $payload = Payload::fromArray([
            Command::getKey() => $command->toSerializedString()
        ]);
        return new self(
            EventId::create(), $resourceId, $payload, $creator->toAuditLogEventAuthUserPayload(), $occurredAt
        );
    }

    public static function getResourceType(): ResourceType
    {
        return ResourceType::fromString(QueueJob::class);
    }

    public function getJobId(): QueueJobId
    {
        return QueueJobId::fromString($this->getResourceId()->toString());
    }

    public function getCommand(): Command
    {
        $commandPayload = $this->getPayload()->toArray()[Command::getKey()];
        return Command::fromSerializedString($commandPayload);
    }

    public function mustBeLogged(): bool
    {
        return false;
    }
}