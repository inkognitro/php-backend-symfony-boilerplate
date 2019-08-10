<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\Payload;

final class AttributeChange
{
    private $attributeKey;
    private $previousValue;
    private $value;

    /**
     * @param $previousValue mixed
     * @param $value mixed
     */
    public function __construct(string $attributeKey, $previousValue, $value)
    {
        if($previousValue !== null && !is_scalar($previousValue)) {
            throw new \InvalidArgumentException('$previousValue must be NULL or a scalar type');
        }
        if($value !== null && !is_scalar($value)) {
            throw new \InvalidArgumentException('$value must be NULL or a scalar type');
        }
        $this->attributeKey = $attributeKey;
        $this->previousValue = $previousValue;
        $this->value = $value;
    }

    public function getAttributeKey(): string
    {
        return $this->attributeKey;
    }

    /** @return mixed */
    public function getValue()
    {
        return $this->value;
    }

    public static function fromChange(string $attributeKey, $previousValue, $value): self
    {
        return new self($attributeKey, $previousValue, $value);
    }

    public static function fromCreation(string $attributeKey, $value): self
    {
        return new self($attributeKey, null, $value);
    }

    public function toPayloadArray(): array
    {
        return [
            'attribute' => $this->attributeKey,
            'previousValue' => $this->previousValue,
            'value' => $this->value,
        ];
    }

    public static function fromPayloadArray(array $data): self
    {
        return new self(
            $data['attribute'],
            $data['previousValue'],
            $data['value']
        );
    }
}