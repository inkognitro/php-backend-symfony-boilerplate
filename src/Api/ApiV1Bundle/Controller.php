<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Packages\Common\Application\Command\Validation\MessageBag;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Packages\Common\Application\CommandBus;

final class Controller extends AbstractController
{
    private $responseFactory;
    private $commandBus;

    public function __construct(ResponseFactory $responseFactory, CommandBus $commandBus)
    {
        $this->responseFactory = $responseFactory;
        $this->commandBus = $commandBus;
    }

    public function handle(string $path): Response
    {
        $handlerResponse = new ValidationErrorResponse(new MessageBag(), new MessageBag());
        //$handlerResponse = $this->commandBus->handle();
        $request = Request::createFromGlobals();
        $response = $this->responseFactory->createFromHandlerResponse($request, $handlerResponse);
        return $response;
    }
}