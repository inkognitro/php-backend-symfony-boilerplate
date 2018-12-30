<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint;

use AppWebApiV1Bundle\Response\Response;
use Symfony\Component\HttpFoundation\Request;

interface Endpoint
{
    public function handle(Request $request, array $urlParams): Response;
    public function getSchema(): EndpointSchema;
}