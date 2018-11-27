<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Packages\Common\Application\CommandBus;

final class Controller extends AbstractController
{
    private $router;
    private $commandBus;

    public function __construct(Router $router, CommandBus $commandBus)
    {
        $this->router = $router;
        $this->commandBus = $commandBus;
    }

    public function handle(string $path): Response
    {
        //$handlerResponse = $this->commandBus->handle();
        $request = Request::createFromGlobals();
        $response = $this->router->handle($path, $request);
        return $response;
    }
}