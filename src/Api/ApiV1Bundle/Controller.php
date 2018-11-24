<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use App\Packages\Resources\Application\SaveResource;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Packages\Common\Application\Command\CommandBus;

final class Controller extends AbstractController
{
    private $router;
    private $commandBus; //todo: remove

    public function __construct(Router $router, CommandBus $commandBus)
    {
        $this->router = $router;
        $this->commandBus = $commandBus;
    }

    public function handle(string $path): Response
    {
        $command = new SaveResource('user', ['id' => 'blablabla']);
        $handlerResponse = $this->commandBus->handle($command);
        die(print_r($handlerResponse, true));

        $request = Request::createFromGlobals();
        $response = $this->router->handle($path, $request);
        return $response;
    }
}