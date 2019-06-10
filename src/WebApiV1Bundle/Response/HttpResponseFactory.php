<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class HttpResponseFactory
{
    public function create(Response $response, Request $request): HttpResponse
    {
        $httpResponse = new HttpResponse();
        $httpResponse->prepare($request);
        $httpResponse->setStatusCode($response->getHttpStatusCode());
        $httpResponse->setCharset('UTF-8');

        if ($response instanceof JsonResponse) {
            $httpResponse->setContent($response->toJson());
            $httpResponse->headers->set('Content-Type', 'application/json');
            return $httpResponse;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($response) . '" is not supported!');
    }
}