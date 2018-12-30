<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint;

final class Endpoints
{
    private $endpoints;

    public function __construct(iterable $endpoints)
    {
        $this->endpoints = $endpoints;
    }

    public function toCollection(): iterable
    {
        return $this->endpoints;
    }
}