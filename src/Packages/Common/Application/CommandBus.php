<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\Success;
use App\Packages\Common\Domain\StateManager;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class CommandBus
{
    private $stateManager;
    private $serviceContainer;

    public function __construct(StateManager $stateManager, ContainerInterface $serviceContainer)
    {
        $this->stateManager = $stateManager;
        $this->serviceContainer = $serviceContainer;
    }

    public function handle(Command $command): Response
    {
        $transactionSavePointName = $this->createSavePointName();
        $this->stateManager->beginTransaction($transactionSavePointName);
        try {
            $commandHandler = $this->serviceContainer->get($command->getHandlerClass());
            $handlerResponse = $commandHandler->handle($command);
            if (!$handlerResponse instanceof Success) {
                $this->stateManager->rollbackTransaction($transactionSavePointName);
                return $handlerResponse;
            }
            $this->stateManager->commitTransaction();
            return $handlerResponse;
        } catch (Exception $e) {
            $this->stateManager->rollbackTransaction($transactionSavePointName);
            throw $e;
        }
    }

    private function createSavePointName(): string
    {
        return 'a' . str_replace('-', '', Uuid::uuid4()->toString());
    }
}