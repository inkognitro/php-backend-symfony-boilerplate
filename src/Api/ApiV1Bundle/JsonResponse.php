<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

interface JsonResponse extends Response
{
    public function toJson(): string;
}