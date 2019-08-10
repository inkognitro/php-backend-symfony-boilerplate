<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;

use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;

final class ResourceChange
{
    private $resourceTypeId;
    private $resourceId;
    private $attributeChanges;

    private function __construct(ResourceTypeId $resourceTypeId, ResourceId $resourceId, AttributeChanges $attributeChanges)
    {
        $this->resourceTypeId = $resourceTypeId;
        $this->resourceId = $resourceId;
        $this->attributeChanges = $attributeChanges;
    }

    public static function create(ResourceTypeId $resourceTypeId, ResourceId $resourceId): self
    {
        return new self($resourceTypeId, $resourceId, AttributeChanges::create());
    }

    public function getResourceId(): ResourceId
    {
        return $this->resourceId;
    }

    public function getResourceTypeId(): ResourceTypeId
    {
        return $this->resourceTypeId;
    }

    public function findAttributeChange(string $attributeKey): ?AttributeChange
    {
        return $this->attributeChanges->find($attributeKey);
    }

    /**
     * @param $previousValue mixed
     * @param $value mixed
     */
    public function addAttributeChangeFromScalars(string $attributeKey, $value, $previousValue = null): self
    {
        return $this->modifyByArray([
            'attributeChanges' => $this->attributeChanges->add(
                new AttributeChange($attributeKey, $previousValue, $value)
            )
        ]);
    }

    private function modifyByArray(array $data): self
    {
        return new self(
            ($data['resourceTypeId'] ?? $this->resourceTypeId),
            ($data['resourceId'] ?? $this->resourceId),
            ($data['attributeChanges'] ?? $this->attributeChanges)
        );
    }

    public function toPayloadArray(): array
    {
        return [
            'resourceTypeId' => $this->resourceTypeId,
            'resourceId' => $this->resourceId,
            'attributeChanges' => $this->attributeChanges
        ];
    }

    public static function fromPayloadArray(array $data): self
    {
        return new self(
            $data['resourceTypeId'],
            $data['resourceId'],
            $data['attributeChanges']
        );
    }
}