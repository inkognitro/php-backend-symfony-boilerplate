<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;

final class AttributeChanges
{
    private $attributeChanges;

    /** @param $attributeChanges AttributeChange[] */
    private function __construct(array $attributeChanges)
    {
        $this->attributeChanges = $attributeChanges;
    }

    public static function create(): self
    {
        return new self([]);
    }

    /** @return AttributeChange[] */
    public function toArray(): array
    {
        return $this->attributeChanges;
    }

    public function add(AttributeChange $attributeChange): self
    {
        return new self(array_merge($this->toArray(), [$attributeChange]));
    }

    public function toPayloadArray(): array
    {
        return array_map(function (AttributeChange $attributeChange): array {
            return $attributeChange->toPayloadArray();
        }, $this->attributeChanges);
    }

    public static function fromPayloadArray(array $data): self
    {
        return new self(
            array_map(function (array $attributeChangeData): AttributeChange {
                return AttributeChange::fromPayloadArray($attributeChangeData);
            }, $data)
        );
    }

    public function find(string $attributeKey): ?AttributeChange
    {
        foreach ($this->attributeChanges as $attributeChange) {
            if ($attributeChange->getAttributeKey() === $attributeKey) {
                return $attributeChange;
            }
        }
        return null;
    }
}