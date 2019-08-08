<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;

final class ResourceChanges
{
    private $resourceChanges;

    /** @param $resourceChanges ResourceChange[] */
    private function __construct(array $resourceChanges)
    {
        $this->resourceChanges = $resourceChanges;
    }

    public static function create(): self
    {
        return new self([]);
    }

    /** @return ResourceChange[] */
    public function toArray(): array
    {
        return $this->resourceChanges;
    }

    public function add(ResourceChange $resourceChange): self
    {
        return new self(array_merge($this->toArray(), [$resourceChange]));
    }

    public function toPayloadArray(): array
    {
        return array_map(function (ResourceChange $resourceChange): array {
            return $resourceChange->toPayloadArray();
        }, $this->resourceChanges);
    }

    public static function fromPayloadArray(array $data): self
    {
        return new self(
            array_map(function (array $resourceChangeData): ResourceChange {
                return ResourceChange::fromPayloadArray($resourceChangeData);
            }, $data)
        );
    }

    public function find(ResourceTypeId $resourceTypeId, ResourceId $resourceId): ?ResourceChange
    {
        foreach ($this->resourceChanges as $resourceChange) {
            if (
                $resourceChange->getResourceTypeId()->equals($resourceTypeId)
                && $resourceChange->getResourceId()->equals($resourceId)
            ) {
                return $resourceChange;
            }
        }
        return null;
    }
}