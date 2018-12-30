<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle\Response;

use App\Api\WebApiV1Bundle\Transformer\Transformer;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse as UnauthorizedHandlerResponse;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Resources\Common\Application\HandlerResponse\ChangeSuccessResponse;
use App\Resources\Common\Application\HandlerResponse\CreationSuccessResponse;
use App\Resources\Common\Application\HandlerResponse\NotFoundResponse as ResourceNotFoundResponse;
use App\Resources\Common\Application\HandlerResponse\RemovalSuccessResponse;

final class ResponseFactory
{
    private $transformer;

    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function createFromHandlerResponse(HandlerResponse $handlerResponse): Response
    {
        if($handlerResponse instanceof UnauthorizedHandlerResponse) {
            return new UnauthorizedResponse();
        }

        if($handlerResponse instanceof ResourceNotFoundResponse) {
            return new NotFoundResponse();
        }

        if($handlerResponse instanceof ValidationErrorResponse) {
            return new BadRequestResponse(
                $handlerResponse->getErrors()->toArray(),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        if($handlerResponse instanceof CreationSuccessResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        if($handlerResponse instanceof ChangeSuccessResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        if($handlerResponse instanceof RemovalSuccessResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        throw new ResponseNotSupportedException('HandlerResponse "' . get_class($handlerResponse) . '" is not supported!');
    }
}