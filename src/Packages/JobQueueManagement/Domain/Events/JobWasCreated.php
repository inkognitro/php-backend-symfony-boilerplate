<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain\Events;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Application\Query\AuditLogEvent\Attributes\ResourceType;
use App\Packages\Common\Application\Query\QueueJob\Attributes\Attributes\Command;
use App\Packages\Common\Application\Query\AuditLogEvent\Attributes\EventId;
use App\Packages\Common\Application\Query\AuditLogEvent\Attributes\OccurredAt;
use App\Packages\Common\Application\Query\AuditLogEvent\Attributes\Payload;
use App\Packages\Common\Application\Query\AuditLogEvent\Attributes\ResourceId;
use App\Packages\Common\Application\Query\QueueJob\QueueJob;
use App\Packages\Common\Application\Query\QueueJob\Attributes\QueueJobId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

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