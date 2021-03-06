<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\Migrations\Migration;
use App\Packages\Common\Installation\Migrations\Migrations;
use App\Packages\Common\Installation\Migrations\V1\MigrationsMigrationV1;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RollbackCommand extends Command
{
    private $connection;
    private $repository;

    public function __construct(DbalConnection $connection, MigrationRepository $repository)
    {
        $this->connection = $connection;
        $this->repository = $repository;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:migrations:rollback');
        $this->setDescription('Rollbacks the migrations of the previous installation batch.');
        $this->setHelp('This command allows you to rollback the previous migration batch of the installed packages.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeRollbacks();
    }

    private function executeRollbacks(): void
    {
        $executedMigrations = $this->repository->findAllExecuted();
        $migrationsToRollback = $this->getMigrationsToRollback($executedMigrations);

        if($migrationsToRollback->isEmpty()) {
            echo "Nothing to rollback!" . PHP_EOL;
            return;
        }

        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();

        foreach($migrationsToRollback->toReverseSortedArray() as $migration) {
            $toSchema = clone $fromSchema;

            $migration->schemaDownBeforeDataRollback($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            $migration->dataRollback($this->connection);

            $fromSchema = clone $toSchema;
            $migration->schemaDown($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            if(!$migration instanceof MigrationsMigrationV1) {
                $this->removeMigrationExecutedEntry($migration);
            }

            echo 'Rolled back: ' . get_class($migration) . PHP_EOL;
        }

        $executedMigrations = $this->repository->findAllExecuted();

        if($executedMigrations->isEmpty()) {
            echo 'Rolled successfully back to point zero.' . PHP_EOL;
            return;
        }

        $batchNumber = $executedMigrations->getHighestBatchNumber();
        echo "Rolled successfully back to batch {$batchNumber}." . PHP_EOL;
    }

    private function getMigrationsToRollback(Migrations $executedMigrations): Migrations
    {
        if($executedMigrations->isEmpty()) {
            return new Migrations([]);
        }

        $latestExecutedBatchNumber = ($executedMigrations->getHighestBatchNumber());

        $migrations = [];
        foreach($executedMigrations->toArray() as $migration) {
            if($migration->getBatchNumber() !== $latestExecutedBatchNumber) {
                continue;
            }
            $migrations[] = $migration;
        }

        return new Migrations($migrations);
    }

    private function removeMigrationExecutedEntry(Migration $migration): void
    {
        $className = get_class($migration);
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->delete('migrations');
        $queryBuilder->andWhere("class_name = {$queryBuilder->createNamedParameter($className)}");
        $queryBuilder->execute();
    }

    private function executeSchemaUpdate(Schema $fromSchema, Schema $toSchema): void
    {
        $migrationSqls = $fromSchema->getMigrateToSql($toSchema, $this->connection->getDatabasePlatform());
        foreach($migrationSqls as $sql) {
            $this->connection->exec($sql);
        }
    }
}