<?php declare(strict_types=1);

namespace App\Resources\Common\Application;

interface Resource
{
    public static function fromArray(array $array);
    public function toArray(): array;
}