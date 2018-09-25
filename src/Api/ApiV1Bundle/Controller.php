<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class Controller
{
    public function handle(string $path): void
    {
        $className = $path


        $request = Request::createFromGlobals();

        if($request->isMethod('GET')) {
            $this->respond($request, $this->handleGet($request));
        }

        if ($request->isMethod('POST')) {
            $this->respond($request, $this->handlePost($request));
        }



        $this->respond($request, $this->getBadRequestResponse());
    }

    private function getBadRequestResponse(): BadRequestResponse
    {
        return BadRequestResponse::createFromMessage('Bad request.');
    }

    private function respond(Request $request, Response $apiResponse): void
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
            $response->send();
            return;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($apiResponse) . '" is not supported!');
    }
}