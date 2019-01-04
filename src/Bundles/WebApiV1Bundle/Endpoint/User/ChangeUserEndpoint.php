<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Endpoint\User;

use App\Bundles\WebApiV1Bundle\Endpoint\AbstractEndpoint;
use App\Bundles\WebApiV1Bundle\Endpoint\EndpointSchema;
use App\Bundles\WebApiV1Bundle\Response\OkResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class ChangeUserEndpoint extends AbstractEndpoint
{
    public function handle(string $userId): HttpResponse
    {
        $request = Request::createFromGlobals();
        $response = new OkResponse([
            'endpoint' => 'ChangeUserEndpoint', 'userId' => $userId
        ], []);
        return $this->createHttpResponse($response, $request);
    }

    public function getSchema(): EndpointSchema
    {
        return EndpointSchema::fromJsonFile(__DIR__ . '/ChangeUserEndpointSchema.json');
    }
}
