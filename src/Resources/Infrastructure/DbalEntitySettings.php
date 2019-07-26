<?php declare(strict_types=1);

namespace App\Resources\Infrastructure;

interface DbalEntitySettings
{
    public function getFieldByAttribute(string $attribute): string;
}