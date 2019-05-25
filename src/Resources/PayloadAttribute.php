<?php declare(strict_types=1);

namespace App\Resources;

abstract class AbstractPayload implements Attribute
{
    protected $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}