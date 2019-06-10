<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class UrlFragment
{
    private $fragment;

    private function __construct(string $fragment)
    {
        $this->fragment = $fragment;
    }

    public static function fromString(string $fragment): self
    {
        return new self($fragment);
    }

    public function toString(): string
    {
        return $this->fragment;
    }
}