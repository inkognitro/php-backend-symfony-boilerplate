<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Endpoint;

use AppWebApiV1Bundle\Response\JsonResponse;
use AppWebApiV1Bundle\Response\ResponseNotSupportedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use AppWebApiV1Bundle\Response\Response as Response;

abstract class AbstractEndpoint implements Endpoint
{
    protected function createHttpResponse(Response $response, Request $request): HttpResponse
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