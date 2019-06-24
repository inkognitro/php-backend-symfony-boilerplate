<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints;

final class Endpoints
{
    private $endpoints;

    public function __construct(iterable $endpoints)
    {
        $this->endpoints = $endpoints;
    }

    public function toIterable(): iterable
    {
        return $this->endpoints;
    }
}