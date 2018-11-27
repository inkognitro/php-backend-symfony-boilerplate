<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Response;

use App\Api\ApiV1Bundle\Response\Response as ApiResponse;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

final class ResponseFactory
{
    public function createFromHandlerResponse(Request $request, HandlerResponse $handlerResponse): HttpResponse
    {
        $apiResponse = $this->getApiResponseFromHandlerResponse($handlerResponse);
        return $this->getHttpResponseFromApiResponse($request, $apiResponse);

    }

    private function getApiResponseFromHandlerResponse(HandlerResponse $handlerResponse): ApiResponse
    {
        if($handlerResponse instanceof UnauthorizedResponse) {
            return new NotAuthorizedResponse();
        }

        if($handlerResponse instanceof ValidationErrorResponse) {
            return new BadRequestResponse()
        }

        throw new ResponseNotSupportedException('HandlerResponse "' . get_class($handlerResponse) . '" is not supported!');
    }

    private function getHttpResponseFromApiResponse(Request $request, ApiResponse $apiResponse): HttpResponse
    {
        $response = new HttpResponse();
        $response->prepare($request);
        $response->setStatusCode($apiResponse->getStatusCode());
        $response->setCharset('UTF-8');

        if ($apiResponse instanceof JsonResponse) {
            $response->setContent($apiResponse->toJson());
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        throw new ResponseNotSupportedException('ApiResponse "' . get_class($apiResponse) . '" is not supported!');
    }
}