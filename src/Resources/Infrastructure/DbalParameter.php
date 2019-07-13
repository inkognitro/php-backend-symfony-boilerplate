<?php declare(strict_types=1);

namespace App\Resources\Infrastructure;

use Ramsey\Uuid\Uuid;

final class DbalParameter
{
    private $name;
    private $value;
    private $type;

    /** @param $value mixed */
    private function __construct(string $name, $value, ?string $type)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    /** @param $value mixed */
    public static function create($value, $type = null): self
    {
        $name = 'param' . str_replace('-', '', Uuid::uuid4()->toString());
        return new self($name, $value, $type);
    }

    /** @return mixed */
    public function getValue()
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?string
    {
        return $this->type;
    }
}