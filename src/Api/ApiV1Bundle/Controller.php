<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Packages\Authentication\Application\UserFactory;
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
    private $authUserFactory;

    public function __construct(HttpResponseFactory $responseFactory, CommandBus $commandBus, UserFactory $authUserFactory)
    {
        $this->responseFactory = $responseFactory;
        $this->commandBus = $commandBus;
        $this->authUserFactory = $authUserFactory;
    }

    public function handle(string $path): Response
    {
        $user = $this->authUserFactory->createFromUserId('287d6446-af61-4451-bc60-85ea545e53b6');
        die(print_r($user));

        $handlerResponse = new ValidationErrorResponse(new MessageBag(), new MessageBag());
        //$handlerResponse = $this->commandBus->handle();
        $request = Request::createFromGlobals();
        $response = $this->responseFactory->createFromHandlerResponse($request, $handlerResponse);
        return $response;
    }
}