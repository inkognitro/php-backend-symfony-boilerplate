<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

final class WebApiV1Bundle extends Bundle
{
    public static function getVersion(): string
    {
        return '1.0.0';
    }

    public static function getBasePath(): string //todo: RoutingLoader must consider this instead of definition in routes.yaml
    {
        return '/v1';
    }
}