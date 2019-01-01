<?php declare(strict_types=1);

namespace App\CLI;

class ColoredTextFactory
{
    public const COLOR_RED = '0;31';
    public const COLOR_GREEN = '0;32';
    public const COLOR_BLUE = '0;34';
    public const COLOR_YELLOW = '1;33';

    public static function createColoredText(string $text, string $color): string
    {
        return "\033[{$color}m{$text}\033[0m";
    }
}