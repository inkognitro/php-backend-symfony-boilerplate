<?php declare(strict_types=1);

namespace App;

class IterableConverter
{
    public static function convertToArray(iterable $iterable): array
    {
        $array = [];
        foreach ($iterable as $element) {
            $array[] = $element;
        }
        return $array;
    }
}
