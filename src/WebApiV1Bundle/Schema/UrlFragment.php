<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class UrlFragment
{
    private $fragments;

    private function __construct(string $fragment)
    {
        $this->fragments = $fragment;
    }

    public static function fromString(string $fragment): self
    {
        return new self($fragment);
    }
}