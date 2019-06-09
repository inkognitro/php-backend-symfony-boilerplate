<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema;

final class UrlFragments
{
    private $fragments;

    /** @param $fragments UrlFragment[] */
    private function __construct(array $fragments)
    {
        $this->fragments = $fragments;
    }

    public static function create(): self
    {
        return new self([]);
    }

    public function add(UrlFragment $fragment): self
    {
        return new self(array_merge($this->fragments, [$fragment]));
    }

    /** @return UrlFragment[] */
    public function toArray(): array
    {
        return $this->fragments;
    }
}