<?php declare(strict_types=1);

namespace App\Api\WebApiV1\Endpoint;

use App\Api\WebApiV1\Response\Response;

interface Endpoint
{
    //public function handle(...$urlParams): Response;
    public static function getSchema(): EndpointSchema;
}