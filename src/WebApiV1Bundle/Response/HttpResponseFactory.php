<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Endpoints\Response;

use App\Utilities\HandlerResponse\Response as HandlerResponse;
use App\WebApiV1Bundle\Response\JsonResponse;
use App\WebApiV1Bundle\Response\ResponseNotSupportedException;
use App\WebApiV1Bundle\Response\Response;
use App\WebApiV1Bundle\Response\SuccessResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class HttpResponseFactory
{
    public function createFromHandlerResponse(HandlerResponse $response, Request $request): HttpResponse
    {
        return $this->createResponse(new SuccessResponse(['foo' => 'bar'], []), $request); //todo
    }

    private function createResponse(Response $response, Request $request): HttpResponse
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