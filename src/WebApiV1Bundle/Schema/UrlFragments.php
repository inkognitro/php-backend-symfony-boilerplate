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

    public static function fromStrings(array $fragments): self
    {
        $fragmentObjects = [];
        foreach($fragments as $fragment) {
            $fragmentObjects[] = UrlFragment::fromString($fragment);
        }
        return new self($fragmentObjects);
    }

    public function add(UrlFragment $fragment): self
    {
        return new self(array_merge($this->fragments, [$fragment]));
    }

    /** @return UrlFragment[] */
    public function toPath(): string
    {
        if(count($this->fragments) === 0) {
            return '';
        }
        $path = '';
        foreach($this->fragments as $fragment) {
            $path .= '/' . $fragment->toString();
        }
        return $path;
    }
}