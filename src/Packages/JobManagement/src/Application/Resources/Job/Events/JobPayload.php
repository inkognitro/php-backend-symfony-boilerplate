<?php declare(strict_types=1);

namespace App\Packages\JobManagement\Application\Resources\Job\Events;

use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\UpdatedAt;
use App\Packages\Common\Application\Resources\Events\AbstractPayload;
use App\Packages\JobManagement\Application\Resources\Job\Job;
use App\Packages\JobManagement\Application\Resources\Job\JobId;

final class JobPayload extends AbstractPayload
{
    public static function fromJob(Job $job, array $additionalPayloadData = []): self
    {
        $updatedAt = ($job->getUpdatedAt() === null ? null : $job->getUpdatedAt()->toString());
        $createdAt = ($job->getCreatedAt() === null ? null : $job->getCreatedAt()->toString());
        $payloadData = array_merge([
            'id' => $job->getId()->toString(),
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ], $additionalPayloadData);
        return new self($payloadData);
    }

    public function toJob(): Job
    {
        $payloadData = $this->data;
        $createdAt = ($payloadData['createdAt'] === null ? null : CreatedAt::fromString($payloadData['createdAt']));
        $updatedAt = ($payloadData['updatedAt'] === null ? null : UpdatedAt::fromString($payloadData['updatedAt']));
        return new Job(
            JobId::fromString($payloadData['id']),
            $createdAt,
            $updatedAt
        );
    }
}