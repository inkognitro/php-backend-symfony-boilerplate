<?php declare(strict_types=1);

namespace AppWebApiV1Bundle\Response;

use AppWebApiV1Bundle\Transformer\Transformer;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse as UnauthorizedHandlerResponse;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceChangedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceNotFoundResponse as ResourceNotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceRemovedResponse;

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