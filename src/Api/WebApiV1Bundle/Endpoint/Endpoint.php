<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle\Endpoint;

use App\Api\WebApiV1Bundle\Response\Response;

interface Endpoint
{
    //public function handle(...$urlParams): Response;
    public function getSchema(): EndpointSchema;
}