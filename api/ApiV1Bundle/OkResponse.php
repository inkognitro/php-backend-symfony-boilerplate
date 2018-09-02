<?php declare(strict_types=1);

final class OkResponse implements Response
{
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function createFromData(array $data): self
    {
        return new self($data);
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}