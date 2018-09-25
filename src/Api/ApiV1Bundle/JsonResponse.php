<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

interface JsonResponse extends Response
{
    public function toJson(): string;
}