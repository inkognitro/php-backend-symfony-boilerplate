<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Resources\Events;

abstract class AbstractPayload
{
    protected $data;

    public function __construct(array $data)
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