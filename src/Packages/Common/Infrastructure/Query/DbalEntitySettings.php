<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\Query;

interface DbalEntitySettings
{
    public function getFieldByAttribute(string $attribute): string;
}