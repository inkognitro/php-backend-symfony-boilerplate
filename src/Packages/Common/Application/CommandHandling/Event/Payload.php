<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling\Event;

final class Payload
{
    private $data;

    private function __construct(array $data)
    {
        $this->data;
    }

    public static function fromData(array $data): self
    {
        return new self($data);
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}