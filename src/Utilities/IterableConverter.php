<?php declare(strict_types=1);

namespace App\Utilities;

/** @deprecated */
class IterableConverter
{
    public static function convertToArray(iterable $iterable): array //todo: get rid of this bullshit class
    {
        $array = [];
        foreach ($iterable as $element) {
            $array[] = $element;
        }
        return $array;
    }
}
