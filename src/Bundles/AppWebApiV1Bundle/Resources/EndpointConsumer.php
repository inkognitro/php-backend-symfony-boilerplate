<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Resources;

use AppWebApiV1Bundle\Endpoint\Endpoint;
use AppWebApiV1Bundle\Response\JsonResponse;
use AppWebApiV1Bundle\Response\ResponseNotSupportedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use AppWebApiV1Bundle\Response\Response as EndpointResponse;

final class EndpointConsumer
{
    public function consume(Endpoint $endpoint, ...$urlParams): HttpResponse
    {
        $request = Request::createFromGlobals();
        $urlParamsAsArray = array_merge(...$urlParams);
        $response = $endpoint->handle($request, $urlParamsAsArray);
        return $this->createHttpResponse($response, $request);
    }

    private function createHttpResponse(EndpointResponse $response, Request $request): HttpResponse
    {
        $httpResponse = new HttpResponse();
        $httpResponse->prepare($request);
        $httpResponse->setStatusCode($response->getStatusCode());
        $httpResponse->setCharset('UTF-8');

        if ($response instanceof JsonResponse) {
            $httpResponse->setContent($response->toJson());
            $httpResponse->headers->set('Content-Type', 'application/json');
            return $httpResponse;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($response) . '" is not supported!');
    }
}