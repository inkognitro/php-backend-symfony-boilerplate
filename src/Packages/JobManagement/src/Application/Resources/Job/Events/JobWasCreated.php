<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Resources\Job\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Application\Resources\Events\EventId;
use App\Packages\Common\Application\Resources\Events\OccurredAt;
use App\Packages\JobManagement\Application\Resources\Job\Job;

final class JobWasCreated extends AbstractEvent
{
    public static function occur(Job $job, AuthUser $authUser): self
    {
        $previousPayload = null;
        $occurredAt = OccurredAt::fromNow();
        $payload = JobPayload::fromJob($job, [
            'createdAt' => $occurredAt->toString()
        ]);
        return new self(EventId::create(), $occurredAt, $authUser, $payload, $previousPayload);
    }

    public function getJob(): Job
    {
        /** @var $payload JobPayload */
        $payload = $this->getPayload();
        return $payload->toJob();
    }

    public function getResource(): AbstractResource
    {
        return $this->getJob();
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}