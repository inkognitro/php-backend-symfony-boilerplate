<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command\Params;

final class Text
{
    private $text;

    private function __construct(string $text)
    {
        $this->text = $text;
    }

    public static function fromString(string $text): self
    {
        return new self($text);
    }

    public function toString(): string
    {
        return $this->text;
    }

    public function toTrimmedString(): string
    {
        return trim($this->text);
    }

    public function toTrimmedLowerCaseString(): string
    {
        return strtolower($this->toTrimmedString());
    }
}