<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Controller;

use App\Api\ApiV1Bundle\HttpResponseFactory;
use App\Packages\Authentication\Application\UserFactory;
use App\Packages\Common\Application\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends AbstractController
{
    protected $authUserFactory;
    protected $commandBus;
    protected $httpResponseFactory;

    public function __construct(
        UserFactory $authUserFactory,
        CommandBus $commandBus,
        HttpResponseFactory $httpResponseFactory
    ) {
        $this->authUserFactory = $authUserFactory;
        $this->commandBus = $commandBus;
        $this->httpResponseFactory = $httpResponseFactory;
    }

    protected function createRequest(): Request
    {
        return Request::createFromGlobals();
    }
}