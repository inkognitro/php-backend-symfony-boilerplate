<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\Migrations\AbstractMigration;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:migrations:migrate');
        $this->setDescription('Migrates the migrations of the next installation batch.');
        $this->setHelp('This command allows you to migrate the next migration batch of the installed packages.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createMigrationsTable();
        $this->executeMigrations();
    }

    private function executeMigrations(): void
    {
        $migrations = $this->getMigrationsToMigrate();

        if(count($migrations->toCollection()) === 0) {
            echo "Nothing to migrate!";
            return;
        }

        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();

        foreach($migrations->toCollection() as $migration) {
            $toSchema = clone $fromSchema;
            $migration->schemaUp($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);
            $this->addMigrationExecutedEntry($migration);
            $fromSchema = $toSchema;
        }

        $batchNumber = $migrations->toCollection()[0]->getBatchNumber();
        echo "Migrated successfully to batch {$batchNumber}.";
    }

    private function getMigrationsToMigrate(): Migrations
    {
        $repository = new MigrationRepository($this->connection);
        $notExecutedMigrations = $repository->findAllNotExecuted();

        if(count($notExecutedMigrations->toCollection()) === 0) {
            return new Migrations([]);
        }

        $executedMigrations = $repository->findAllExecuted();
        $latestBatchNumber = ($executedMigrations->getHighestBatchNumber());

        $nextBatchesMigrations = $notExecutedMigrations->findAllWithHigherBatchNumber($latestBatchNumber);
        if(count($nextBatchesMigrations->toCollection()) === 0) {
            return new Migrations([]);
        }

        $batchNumber = $nextBatchesMigrations->getLowestBatchNumber();

        $migrations = [];
        foreach($nextBatchesMigrations->toCollection() as $migration) {
            if($migration->getBatchNumber() !== $batchNumber) {
                continue;
            }
            $migrations[] = $migration;
        }

        return new Migrations($migrations);
    }

    private function addMigrationExecutedEntry(AbstractMigration $migration): void
    {
        $className = get_class($migration);
        $batchNumber = $migration->getBatchNumber();
        $utc = new DateTimeZone('UTC');
        $executedAt = (new DateTimeImmutable())->setTimezone($utc)->format('Y-m-d H:i:s');
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('migrations');
        $queryBuilder->setValue('class_name', $queryBuilder->createNamedParameter($className));
        $queryBuilder->setValue('batch_number', $queryBuilder->createNamedParameter($batchNumber));
        $queryBuilder->setValue('executed_at', $queryBuilder->createNamedParameter($executedAt));
        $queryBuilder->execute();
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();
        try {
            $fromSchema->getTable('migrations');
        } catch (SchemaException $e) {
            $toSchema = clone $fromSchema;
            $migration = new MigrationsMigration();
            $migration->schemaUp($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);
        }
    }

    private function executeSchemaUpdate(Schema $fromSchema, Schema $toSchema): void
    {
        $migrationSqls = $fromSchema->getMigrateToSql($toSchema, $this->connection->getDatabasePlatform());
        foreach($migrationSqls as $sql) {
            $this->connection->exec($sql);
        }
    }
}