<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Controller;

use App\Api\ApiV1Bundle\ResponseFactory;
use App\Packages\Common\Application\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends AbstractController
{
    protected $commandBus;
    protected $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactory $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    protected function getRequest(): Request
    {
        return Request::createFromGlobals();
    }
}