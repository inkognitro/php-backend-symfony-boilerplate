<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use Ramsey\Uuid\Uuid;

final class DbalParameter
{
    private $name;
    private $value;
    private $type;

    /**
     * @param $value mixed
     * @param $type string|int|null
     */
    private function __construct(string $name, $value, $type)
    {
        $this->name = $name;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     * @param $value mixed
     * @param $type string|int|null
     */
    public static function create($value, $type = null): self
    {
        return new self(self::createName(), $value, $type);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /** @return mixed */
    public function getValue()
    {
        return $this->value;
    }

    /** @return string|int|null */
    public function getType()
    {
        return $this->type;
    }

    private static function createName(): string
    {
        return 'dcValue' . str_replace('-', '', Uuid::uuid4()->toString());
    }
}