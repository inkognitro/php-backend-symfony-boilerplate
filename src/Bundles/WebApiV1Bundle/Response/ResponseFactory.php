<?php declare(strict_types=1);

namespace App\Bundles\WebApiV1Bundle\Response;

use App\Bundles\WebApiV1Bundle\Transformer\Transformer;
use App\Utilities\HandlerResponse\Response;
use App\Utilities\HandlerResponse\UnauthorizedResponse as UnauthorizedHandlerResponse;
use App\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Utilities\HandlerResponse\ResourceChangedResponse;
use App\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Utilities\HandlerResponse\ResourceNotFoundResponse as ResourceNotFoundResponse;
use App\Utilities\HandlerResponse\ResourceRemovedResponse;

final class ResponseFactory
{
    private $transformer;

    public function __construct(Transformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function create(Response $handlerResponse): Response
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

        if($handlerResponse instanceof ResourceCreatedResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        if($handlerResponse instanceof ResourceChangedResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        if($handlerResponse instanceof ResourceRemovedResponse) {
            return new OkResponse(
                $this->transformer->transform($handlerResponse->getResource()),
                $handlerResponse->getWarnings()->toArray()
            );
        }

        throw new ResponseNotSupportedException('HandlerResponse "' . get_class($handlerResponse) . '" is not supported!');
    }
}