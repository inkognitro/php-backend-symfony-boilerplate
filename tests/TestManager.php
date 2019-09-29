<?php declare(strict_types=1);

namespace App\Tests;

use App\Packages\AccessManagement\Application\Query\AuthUserFactory;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Domain\StateManager;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use Ramsey\Uuid\Uuid;

final class TestManager
{
    private $transactionSavepointName;
    private $stateManager;
    private $commandBus;
    private $authUserFactory;
    private $usersQueryHandler;

    public function __construct(
        StateManager $stateManager,
        CommandBus $commandBus,
        AuthUserFactory $authUserFactory,
        UsersQueryHandler $usersQueryHandler
    ) {
        $this->stateManager = $stateManager;
        $this->commandBus = $commandBus;
        $this->authUserFactory = $authUserFactory;
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function setUp(): void
    {
        $this->transactionSavepointName = 'a' . str_replace('-', '', Uuid::uuid4()->toString());
        $this->stateManager->beginTransaction($this->transactionSavepointName);
    }

    public function tearDown(): void
    {
        $this->stateManager->rollbackTransaction($this->transactionSavepointName);
    }

    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }

    public function getAuthUserFactory(): UsersQueryHandler
    {
        return $this->usersQueryHandler;
    }

    public function getUsersQueryHandler(): UsersQueryHandler
    {
        return $this->usersQueryHandler;
    }
}
