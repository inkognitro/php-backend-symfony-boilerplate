<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Controller extends AbstractController
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function handle(string $path): Response
    {
        $request = Request::createFromGlobals();
        $response = $this->router->handle($path, $request);
        return $response;
    }
}