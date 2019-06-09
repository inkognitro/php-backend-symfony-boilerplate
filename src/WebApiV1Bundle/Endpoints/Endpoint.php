<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints;

use App\WebApiV1Bundle\Schema\EndpointSchema;

interface Endpoint
{
    public static function getSchema(): EndpointSchema;
}