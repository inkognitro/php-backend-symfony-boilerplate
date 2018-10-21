<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Api\ApiV1Bundle\Response\BadRequestResponse;
use App\Api\ApiV1Bundle\Response\JsonResponse;
use App\Api\ApiV1Bundle\Response\NotAuthorizedResponse;
use App\Api\ApiV1Bundle\Response\NotFoundResponse;
use App\Api\ApiV1Bundle\Response\OkResponse;
use App\Api\ApiV1Bundle\Response\Response;
use App\Api\ApiV1Bundle\Response\ResponseNotSupportedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class Router
{
    public function handle(string $path, Request $request): HttpResponse
    {
        die('path = ' . $path);

        //todo: generate api response and return it

        return $this->getHttpResponse($request, BadRequestResponse::createFromMessage('Bad request.'));
    }

    private function getHttpResponse(Request $request, Response $apiResponse): HttpResponse
    {
        $responseToStatusCodeMapping = [
            OkResponse::class => HttpResponse::HTTP_OK,
            BadRequestResponse::class => HttpResponse::HTTP_BAD_REQUEST,
            NotFoundResponse::class => HttpResponse::HTTP_NOT_FOUND,
            NotAuthorizedResponse::class => HttpResponse::HTTP_UNAUTHORIZED,
        ];

        $response = new HttpResponse();
        $response->prepare($request);
        $response->setStatusCode($responseToStatusCodeMapping[get_class($apiResponse)]);
        $response->setCharset('UTF-8');

        if ($apiResponse instanceof JsonResponse) {
            $response->setContent($apiResponse->toJson());
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($apiResponse) . '" is not supported!');
    }
}