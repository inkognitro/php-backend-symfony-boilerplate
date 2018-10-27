<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling;

use App\Packages\Common\Application\Authorization\PolicyEnforcementPoint;
use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\UnauthorizedResponse;
use Symfony\Component\DependencyInjection\Container;

final class CommandBus
{
    private $serviceContainer;
    private $pep;

    public function __construct(Container $serviceContainer, PolicyEnforcementPoint $pep)
    {
        $this->serviceContainer = $serviceContainer;
        $this->pep = $pep;
    }

    public function handle(Command $command, AuthUser $authUser): HandlerResponse
    {
        $permission = $command->getPermission();
        if ($permission !== null && !$this->pep->isAuthorized($authUser, $permission)) {
            return new UnauthorizedResponse();
        }
        $commandClassName = get_class($command);
        $commandHandlerClassName = $commandClassName . 'Handler';
        $commandHandler = $this->serviceContainer->get($commandHandlerClassName);
        return $commandHandler->handle($command, $authUser);
    }
}