<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Domain\Job\Events;

use App\Packages\Common\Application\Command;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\Job\Attributes\Values\ExecutedAt;
use App\Packages\Common\Domain\Event\AbstractPayload;
use App\Packages\JobQueuing\Domain\Job\Job;
use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;

final class JobPayload extends AbstractPayload
{
    public static function fromJob(Job $job, array $additionalPayloadData = []): self
    {
        $executedAt = ($job->getExecutedAt() === null ? null : $job->getExecutedAt()->toString());
        $createdAt = ($job->getCreatedAt() === null ? null : $job->getCreatedAt()->toString());
        $payloadData = array_merge([
            'id' => $job->getId()->toString(),
            'command' => serialize($job->getCommand()),
            'createdAt' => $createdAt,
            'executedAt' => $executedAt,
        ], $additionalPayloadData);
        return new self($payloadData);
    }

    public function toJob(): Job
    {
        $payloadData = $this->data;

        /** @var $command Command */
        $command = unserialize($payloadData['command']);
        $createdAt = ($payloadData['createdAt'] === null ? null : CreatedAt::fromString($payloadData['createdAt']));
        $executedAt = ($payloadData['executedAt'] === null ? null : ExecutedAt::fromString($payloadData['executedAt']));

        return new Job(
            JobId::fromString($payloadData['id']),
            $command,
            $createdAt,
            $executedAt
        );
    }
}