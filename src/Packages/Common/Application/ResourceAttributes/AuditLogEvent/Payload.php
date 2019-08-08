<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload\ResourceChange;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload\ResourceChanges;

final class Payload
{
    private $specificData;
    private $resourceChanges;

    private function __construct(ResourceChanges $resourceChanges, array $specificData)
    {
        $this->resourceChanges = $resourceChanges;
        $this->specificData = $specificData;
    }

    public static function create(): self
    {
        return new self(ResourceChanges::create(), []);
    }

    public function addResourceChange(ResourceChange $resourceChange): self
    {
        return $this->modifyByArray([
            'resourceChanges' => $this->resourceChanges->add($resourceChange)
        ]);
    }

    public function findResourceChange(ResourceTypeId $resourceTypeId, ResourceId $resourceId): ?ResourceChange
    {
        return $this->resourceChanges->find($resourceTypeId, $resourceId);
    }

    private function modifyByArray(array $data): self
    {
        return new self(
            ($data['resourceChanges'] ?? $this->resourceChanges),
            ($data['specificData'] ?? $this->specificData)
        );
    }

    private function toArray(): array
    {
        return [
            'resourceChanges' => $this->resourceChanges->toPayloadArray(),
            'specificData' => $this->specificData,
        ];
    }

    private static function fromArray(array $data): self
    {
        return new self(
            ResourceChanges::fromPayloadArray($data['resourceChanges']),
            $data['specificData']
        );
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $json): self
    {
        $data = json_decode($json, true);
        return self::fromArray($data);
    }
}