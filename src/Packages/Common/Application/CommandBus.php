<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

use App\Utilities\HandlerResponse\Response;
use App\Utilities\HandlerResponse\Success;
use App\Packages\Common\Domain\CommandHandler;
use App\Packages\Common\Domain\StateManager;
use Exception;
use Ramsey\Uuid\Uuid;

final class CommandBus
{
    private $stateManager;
    private $commandHandler;

    public function __construct(StateManager $stateManager, CommandHandler $commandHandler)
    {
        $this->stateManager = $stateManager;
        $this->commandHandler = $commandHandler;
    }

    public function handle(Command $command): Response
    {
        $transactionSavePointName = $this->createSavePointName();
        $this->stateManager->beginTransaction($transactionSavePointName);
        try {
            $response = $this->commandHandler->handle($command);
            if (!$response instanceof Success) {
                $this->stateManager->rollbackTransaction($transactionSavePointName);
                return $response;
            }
            $this->stateManager->commitTransaction();
            return $response;
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