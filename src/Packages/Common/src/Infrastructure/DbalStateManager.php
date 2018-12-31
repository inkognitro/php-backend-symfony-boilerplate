<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Application\Command\StateManager;

final class DbalStateManager implements StateManager
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function beginTransaction(string $savePointName): void
    {
        $this->connection->beginTransaction();
        $this->connection->createSavepoint($savePointName);
    }

    public function commitTransaction(): void
    {
        $this->connection->commit();
    }

    public function rollbackTransaction(string $savePointName): void
    {
        $this->connection->rollbackSavepoint($savePointName);
    }
}