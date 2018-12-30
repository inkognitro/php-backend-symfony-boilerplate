<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint\User;

use AppWebApiV1Bundle\Endpoint\Endpoint;
use AppWebApiV1Bundle\Endpoint\EndpointSchema;
use AppWebApiV1Bundle\Response\Response;
use Symfony\Component\HttpFoundation\Request;

final class ChangeUserEndpoint implements Endpoint
{
    public function handle(Request $request, array $params): Response
    {
        die('ChangeUserEndpoint ok! params = ' . print_r($params, true));
    }

    public function getSchema(): EndpointSchema
    {
        return EndpointSchema::fromJsonFile(__DIR__ . '/ChangeUserEndpointSchema.json');
    }
}
