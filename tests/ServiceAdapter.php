<?php declare(strict_types=1);

namespace App\Tests;

use App\Packages\Common\Application\StateManager;
use App\Packages\Common\Infrastructure\DbalConnection;
use Doctrine\DBAL\Connection;

final class ServiceAdapter
{
    protected $stateManager;
    protected $connection;

    public function __construct(StateManager $stateManager, DbalConnection $connection)
    {
        $this->stateManager = $stateManager;
        $this->connection = $connection;
    }

    public function getStateManager(): StateManager
    {
        return $this->stateManager;
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }
}
