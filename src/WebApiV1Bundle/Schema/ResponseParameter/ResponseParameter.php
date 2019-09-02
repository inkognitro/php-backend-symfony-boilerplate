<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\ResponseParameter;

interface ResponseParameter
{
    public function toOpenApiV2Array(): array;
}