<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes;

interface Attribute
{
    public static function getPayloadKey(): string;
}