<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints;

use App\WebApiV1Bundle\Schema\EndpointSchema;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

interface Endpoint
{
    public function handle(): HttpResponse;
    public static function getSchema(): EndpointSchema;
}