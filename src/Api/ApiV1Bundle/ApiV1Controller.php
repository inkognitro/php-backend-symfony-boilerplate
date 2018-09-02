<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class ApiV1Controller
{
    protected function respond(Request $request, JsonResponse $apiResponse): HttpResponse
    {
        $responseToStatusCodeMapping = [
            OkResponse::class => HttpResponse::HTTP_OK,
            BadRequestResponse::class => HttpResponse::HTTP_BAD_REQUEST,
            NotFoundResponse::class => HttpResponse::HTTP_NOT_FOUND,
        ];
        $response = new HttpResponse();
        $response->prepare($request);
        $response->setContent($apiResponse->toJson());
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode($responseToStatusCodeMapping[get_class($apiResponse)]);
        $response->setCharset('UTF-8');
        return $response;
    }
}