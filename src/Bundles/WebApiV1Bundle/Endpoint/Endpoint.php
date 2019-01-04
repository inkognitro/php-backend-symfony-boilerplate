<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Endpoint;

interface Endpoint
{
    public function getSchema(): EndpointSchema;
}