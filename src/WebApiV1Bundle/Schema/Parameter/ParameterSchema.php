<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Schema\Parameter;

interface ParameterSchema
{
    public function toRequestParameterOpenApiV2Array(): array;
    public function toResponseParameterOpenApiV2Array(): array;
}