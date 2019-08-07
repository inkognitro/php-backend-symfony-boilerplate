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

    public function hasLineBreaks(): bool
    {
        return (!!strstr($this->text, PHP_EOL));
    }

    public function toLowerCaseString(): string
    {
        return strtolower($this->text);
    }
}