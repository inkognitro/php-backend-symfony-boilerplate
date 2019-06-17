<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Response;

final class JsonPlainDataSuccessResponse implements JsonResponse
{
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
    }

    public static function fromData(array $data): self
    {
        return new self($data);
    }

    public static function getHttpStatusCode(): int
    {
        return Response::HTTP_OK;
    }

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}