<?php declare(strict_types=1);

namespace App\Packages\Common\Domain;

use App\Packages\Common\Application\Command;
use App\Utilities\HandlerResponse\Response;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CommandHandler
{
    private $serviceContainer;

    public function __construct(ContainerInterface $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function handle(Command $command): Response
    {
        $commandHandler = $this->serviceContainer->get($command->getHandlerClass());
        return $commandHandler->handle($command);
    }
}