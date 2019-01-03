<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
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

        if(count($migrationsToRollback->toCollection()) === 0) {
            echo "Nothing to rollback!";
            return;
        }

        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();

        foreach($migrationsToRollback->toCollection() as $migration) {
            $toSchema = clone $fromSchema;

            $migration->schemaDownBeforeDataRollback($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            $migration->dataRollback($this->connection);

            $fromSchema = clone $toSchema;
            $migration->schemaDown($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            $this->removeMigrationExecutedEntry($migration);
        }

        $executedMigrations = $this->repository->findAllExecuted();

        if(count($executedMigrations->toCollection()) === 0) {
            $this->removeMigrationsTable();
            echo 'Rolled successfully back to point zero.';
            return;
        }

        $batchNumber = $executedMigrations->getHighestBatchNumber();
        echo "Rolled successfully back to batch {$batchNumber}.";
    }

    private function getMigrationsToRollback(Migrations $executedMigrations): Migrations
    {
        if(count($executedMigrations->toCollection()) === 0) {
            return new Migrations([]);
        }

        $latestExecutedBatchNumber = ($executedMigrations->getHighestBatchNumber());

        $migrations = [];
        foreach($executedMigrations->toCollection() as $migration) {
            if($migration->getBatchNumber() !== $latestExecutedBatchNumber) {
                continue;
            }
            $migrations[] = $migration;
        }

        return new Migrations($migrations);
    }

    private function removeMigrationExecutedEntry(AbstractMigration $migration): void
    {
        $className = get_class($migration);
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->delete('migrations');
        $queryBuilder->andWhere("class_name = {$queryBuilder->createNamedParameter($className)}");
        $queryBuilder->execute();
    }

    private function removeMigrationsTable(): void
    {
        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();
        try {
            $fromSchema->getTable('migrations');
            $toSchema = clone $fromSchema;
            $migration = new MigrationsMigration();
            $migration->schemaDown($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);
        } catch (SchemaException $e) {}
    }

    private function executeSchemaUpdate(Schema $fromSchema, Schema $toSchema): void
    {
        $migrationSqls = $fromSchema->getMigrateToSql($toSchema, $this->connection->getDatabasePlatform());
        foreach($migrationSqls as $sql) {
            $this->connection->exec($sql);
        }
    }
}