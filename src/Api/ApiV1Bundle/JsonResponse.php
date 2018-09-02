<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

interface JsonResponse
{
    public function toJson(): string;
}