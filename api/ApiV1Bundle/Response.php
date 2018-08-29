<?php declare(strict_types=1);

use \Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final class Response extends SymfonyResponse
{
    public function __construct(array $data, $status)
    {
        $json = json_encode(['data' => $data]);
        parent::__construct($json, SymfonyResponse::HTTP_OK, []);
    }

    public static function createFromData(array $data): self
    {
        return new self($data, SymfonyResponse::HTTP_OK);
    }
}