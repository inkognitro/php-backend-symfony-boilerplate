<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint;

interface Endpoint
{
    public function getSchema(): EndpointSchema;
}