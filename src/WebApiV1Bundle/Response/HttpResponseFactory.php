<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Response;

use App\WebApiV1Bundle\ApiRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class HttpResponseFactory
{
    public function create(Response $response, ApiRequest $request): HttpResponse
    {
        if ($response instanceof JsonResponse) {
            $httpResponse = new HttpResponse();
            $this->prepareHttpResponse($httpResponse, $response, $request);
            $httpResponse->setContent($response->toJson());
            $httpResponse->headers->set('Content-Type', 'application/json');
            return $httpResponse;
        }

        if($response instanceof HtmlResponse) {
            $httpResponse = new HttpResponse();
            $this->prepareHttpResponse($httpResponse, $response, $request);
            $httpResponse->setContent($response->toHtml());
            $httpResponse->headers->set('Content-Type', 'text/html');
            return $httpResponse;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($response) . '" is not supported!');
    }

    private function prepareHttpResponse(HttpResponse $httpResponse, Response $response, ApiRequest $request): HttpResponse
    {
        $httpResponse->prepare($request->toHttpFoundationRequest());
        $httpResponse->setStatusCode($response->getHttpStatusCode());
        $httpResponse->setCharset('UTF-8');
        return $httpResponse;
    }
}