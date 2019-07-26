<?php declare(strict_types=1);

namespace App\Resources\Application;

interface Attribute
{
    public static function getPayloadKey(): string;
}