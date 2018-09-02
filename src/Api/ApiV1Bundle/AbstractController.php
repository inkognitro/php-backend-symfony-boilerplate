<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

abstract class AbstractController
{
    protected function handleGet(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }

    protected function handlePost(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }

    protected function getBadRequestResponse(): BadRequestResponse
    {
        return BadRequestResponse::createFromMessage('Bad request.');
    }

    public function handle(Request $request): void
    {
        if ($request->isMethod('POST')) {
            $this->respond($request, $this->handlePost($request));
        }
        $this->respond($request, $this->handleGet($request));
    }

    protected function respond(Request $request, Response $apiResponse): HttpResponse
    {
        $responseToStatusCodeMapping = [
            OkResponse::class => HttpResponse::HTTP_OK,
            BadRequestResponse::class => HttpResponse::HTTP_BAD_REQUEST,
            NotFoundResponse::class => HttpResponse::HTTP_NOT_FOUND,
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