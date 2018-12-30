<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint\User;

use AppWebApiV1Bundle\Endpoint\AbstractEndpoint;
use AppWebApiV1Bundle\Endpoint\EndpointSchema;
use AppWebApiV1Bundle\Response\OkResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class CreateUserEndpoint extends AbstractEndpoint
{
    public function handle(): HttpResponse
    {
        $request = Request::createFromGlobals();
        $response = new OkResponse([
            'endpoint' => 'CreateUserEndpoint'
        ], []);
        return $this->createHttpResponse($response, $request);
    }

    public function getSchema(): EndpointSchema
    {
        return EndpointSchema::fromJsonFile(__DIR__ . '/CreateUserEndpointSchema.json');
    }
}
