<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Application\Resources\Events;

use App\Packages\Common\Application\Command\Command;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\ExecutedAt;
use App\Packages\Common\Domain\Events\AbstractPayload;
use App\Packages\JobQueuing\Application\Resources\Job\Job;
use App\Packages\JobQueuing\Application\Resources\Job\JobId;

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