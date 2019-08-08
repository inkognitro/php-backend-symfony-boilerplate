<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Domain\Events;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\AuthUserPayload;
use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;
use App\Packages\JobQueueManagement\Application\Query\Job\Job;
use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\Command;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\EventId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\OccurredAt;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\QueueJobId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class JobWasCreated extends AuditLogEvent
{
    public static function occur(QueueJobId $jobId, Command $command, AuthUser $creator): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::create();
        $resourceId = ResourceId::fromString($jobId->toString());
        $payload = Payload::fromArray([
            Command::getPayloadKey() => $command->toSerializedString()
        ]);
        return new self(
            EventId::create(), $resourceId, $payload, AuthUserPayload::fromAuthUser($creator), $occurredAt
        );
    }

    public static function findResourceTypeId(): ResourceTypeId
    {
        return ResourceTypeId::fromString(Job::class);
    }

    public function getJobId(): QueueJobId
    {
        return QueueJobId::fromString($this->findResourceId()->toString());
    }

    public function getCommand(): Command
    {
        $commandPayload = $this->getPayload()->toArray()[Command::getPayloadKey()];
        return Command::fromSerializedString($commandPayload);
    }

    public function mustBeLogged(): bool
    {
        return false;
    }
}