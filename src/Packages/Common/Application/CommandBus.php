<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Command\Command;
use App\Packages\Common\Application\Command\StateManager;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\Success;
use Symfony\Component\DependencyInjection\Container;

final class CommandBus
{
    private $stateManager;
    private $serviceContainer;

    public function __construct(StateManager $stateManager, Container $serviceContainer)
    {
        $this->stateManager = $stateManager;
        $this->serviceContainer = $serviceContainer;
    }

    public function handle(Command $command, AuthUser $authUser): HandlerResponse
    {
        $this->stateManager->noticeChanges();
        $handlerResponse = $this->getHandlerResponseFromCommandExecution($command, $authUser);
        if(!$handlerResponse instanceof Success) {
            $this->stateManager->discardNoticedChanges();
            return $handlerResponse;
        }
        $this->stateManager->saveNoticedChanges();
        $this->triggerEventSubscribers();
        return $handlerResponse;
    }

    private function getHandlerResponseFromCommandExecution(Command $command, AuthUser $authUser): HandlerResponse
    {
        $commandClassName = get_class($command);
        $commandHandlerClassName = $commandClassName . 'Handler';
        $commandHandler = $this->serviceContainer->get($commandHandlerClassName);
        return $commandHandler->handle($command, $authUser);
    }

    private function triggerEventSubscribers(): void
    {
        //todo implement subscribers for e.g. filesystem changes, email jobs etc.
    }
}