<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Api\ApiV1Bundle\Response\JsonResponse;
use App\Api\ApiV1Bundle\Response\ResponseFactory as ApiResponseFactory;
use App\Api\ApiV1Bundle\Response\Response as ApiResponse;
use App\Api\ApiV1Bundle\Response\ResponseNotSupportedException;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class HttpResponseFactory
{
    private $apiResponseFactory;

    public function __construct(ApiResponseFactory $apiResponseFactory)
    {
        $this->apiResponseFactory = $apiResponseFactory;
    }

    public function createFromHandlerResponse(HandlerResponse $handlerResponse, ?Request $request = null): HttpResponse
    {
        $apiResponse = $this->apiResponseFactory->createFromHandlerResponse($handlerResponse);
        return $this->createFromApiResponse($apiResponse, $request);
    }

    public function createFromApiResponse(ApiResponse $apiResponse, ?Request $request = null): HttpResponse
    {
        $response = new HttpResponse();
        if($request !== null) {
            $response->prepare($request);
        }
        $response->setStatusCode($apiResponse->getStatusCode());
        $response->setCharset('UTF-8');

        if ($apiResponse instanceof JsonResponse) {
            $response->setContent($apiResponse->toJson());
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        throw new ResponseNotSupportedException('Response class "' . get_class($apiResponse) . '" is not supported!');
    }
}