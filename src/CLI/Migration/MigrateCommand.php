<?php declare(strict_types=1);

namespace App\CLI\Migration;

use App\CLI\ColoredTextFactory;
use App\Packages\Common\Infrastructure\DbalConnection;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Type;
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
        $this->setName('app:migration:migrate');
        $this->setDescription('Migrates the database tables.');
        $this->setHelp('This command allows you to migrate the tables of the installed packages to the next version.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createMigrationsTable();
        $this->executeMigrations();
    }

    private function executeMigrations(): void
    {
        $schemaManager = $this->connection->getSchemaManager();
        $fromSchema = $schemaManager->createSchema();

        $repository = new MigrationRepository($this->connection);
        $executedMigrations = $repository->getAllExecuted();
        $version = ($executedMigrations->getLatestVersion() + 1);

        foreach($repository->getAllRegistered()->toCollection() as $migration) {
            if($executedMigrations->has($migration)) {
                continue;
            }

            if($migration->getVersion() > $version) {
                continue;
            }

            $toSchema = clone $fromSchema;
            $migration->up($toSchema);
            $this->executeSchemaUpdate($fromSchema, $toSchema);
            $this->addMigrationExecutedEntry(get_class($migration), $version);
            $fromSchema = $toSchema;
        }

        echo ColoredTextFactory::createColoredText(
            "Migrated successful to version {$version}", ColoredTextFactory::COLOR_GREEN
        );
    }

    private function addMigrationExecutedEntry(string $className, int $version): void
    {
        $utc = new DateTimeZone('UTC');
        $executedAt = (new DateTimeImmutable())->setTimezone($utc)->format('Y-m-d H:i:s');
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('migrations');
        $queryBuilder->setValue('class_name', $queryBuilder->createNamedParameter($className));
        $queryBuilder->setValue('executed_at', $queryBuilder->createNamedParameter($executedAt));
        $queryBuilder->setValue('version', $queryBuilder->createNamedParameter($version));
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
            $table = $toSchema->createTable('migrations');
            $table->addColumn('class_name', Type::STRING);
            $table->addColumn('executed_at', Type::DATETIME);
            $table->addColumn('version', Type::INTEGER);
            $table->setPrimaryKey(['class_name']);
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