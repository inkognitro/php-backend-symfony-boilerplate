<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

final class OkResponse implements JsonResponse
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