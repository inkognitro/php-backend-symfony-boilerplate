<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Utilities\DateTimeFactory;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\Migrations\Migration;
use App\Packages\Common\Installation\Migrations\MigrationRepository;
use App\Packages\Common\Installation\Migrations\Migrations;
use Doctrine\DBAL\Schema\Schema;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateCommand extends Command
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
        $this->setName('app:migrations:migrate');
        $this->setDescription('Migrates the migrations of the next installation batch.');
        $this->setHelp('This command allows you to migrate the next migration batch of the installed packages.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrations = $this->getMigrationsToMigrate();

        if (count($migrations->toArray()) === 0) {
            echo "Nothing to migrate!" . PHP_EOL;
            return;
        }

        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();

        foreach ($migrations->toArray() as $migration) {
            $toSchema = clone $fromSchema;

            $migration->schemaUp($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            $migration->dataMigration($this->connection);

            $fromSchema = clone $toSchema;
            $migration->schemaUpAfterDataMigration($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);

            $this->addMigrationExecutedEntry($migration);
            echo 'Migrated: ' . get_class($migration) . PHP_EOL;
        }

        $batchNumber = $migrations->toArray()[0]->getBatchNumber();
        echo "Migrated successfully to batch {$batchNumber}." . PHP_EOL;
    }

    private function getMigrationsToMigrate(): Migrations
    {
        $notExecutedMigrations = $this->repository->findAllNotExecuted();

        if (count($notExecutedMigrations->toArray()) === 0) {
            return new Migrations([]);
        }

        $executedMigrations = $this->repository->findAllExecuted();
        $latestBatchNumber = ($executedMigrations->getHighestBatchNumber());

        $nextBatchesMigrations = $notExecutedMigrations->findAllWithHigherBatchNumber($latestBatchNumber);
        if (count($nextBatchesMigrations->toArray()) === 0) {
            return new Migrations([]);
        }

        $batchNumber = $nextBatchesMigrations->getLowestBatchNumber();

        $migrations = [];
        foreach ($nextBatchesMigrations->toArray() as $migration) {
            if ($migration->getBatchNumber() !== $batchNumber) {
                continue;
            }
            $migrations[] = $migration;
        }

        return new Migrations($migrations);
    }

    private function addMigrationExecutedEntry(Migration $migration): void
    {
        $className = get_class($migration);
        $batchNumber = $migration->getBatchNumber();
        $batchSequenceNumber = $migration->getBatchSequenceNumber();
        $executedAt = DateTimeFactory::create();
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('migrations');
        $queryBuilder->setValue('class_name', $queryBuilder->createNamedParameter($className));
        $queryBuilder->setValue('batch_number', $queryBuilder->createNamedParameter($batchNumber));
        $queryBuilder->setValue('batch_sequence_number', $queryBuilder->createNamedParameter($batchSequenceNumber));
        $queryBuilder->setValue('executed_at', $queryBuilder->createNamedParameter($executedAt, 'datetime'));
        $queryBuilder->execute();
    }

    private function executeSchemaUpdate(Schema $fromSchema, Schema $toSchema): void
    {
        $migrationSqls = $fromSchema->getMigrateToSql($toSchema, $this->connection->getDatabasePlatform());
        foreach ($migrationSqls as $sql) {
            $this->connection->exec($sql);
        }
    }
}