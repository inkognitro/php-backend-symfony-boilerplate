<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

interface Attribute
{
    public static function getTypeId(): AttributeTypeId;
    public static function getPayloadKey(): string;
}