<?php declare(strict_types=1);

namespace App\Resources\Application;

interface Resource
{
    public static function createFromRow(array $row);
    public function getLastPersisted();
    public function toCommandData(): array;
}