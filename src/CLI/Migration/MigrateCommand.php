<?php declare(strict_types=1);

namespace App\CLI\Migration;

use App\CLI\Migration\Query\MigrationRepository;
use App\Packages\Common\Infrastructure\DbalConnection;
use DateTimeImmutable;
use DateTimeZone;
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
            $migration->up();
            $this->addMigrationExecutedEntry(get_class($migration), $version);
        }
    }

    private function addMigrationExecutedEntry(string $className, int $version): void
    {
        $utc = new DateTimeZone('UTC');
        $executedAt = (new DateTimeImmutable())->setTimezone($utc)->format('Y-m-d H:i:s');
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('migrations');
        $queryBuilder->setValue('className', $queryBuilder->createNamedParameter($className));
        $queryBuilder->setValue('executedAt', $queryBuilder->createNamedParameter($executedAt));
        $queryBuilder->setValue('version', $queryBuilder->createNamedParameter($version));
    }

    private function createMigrationsTable(): void
    {
        $schema = $this->connection->getSchemaManager()->createSchema();
        try {
            $schema->getTable('migrations');
        } catch (SchemaException $e) {
            $table = $schema->createTable('migrations');
            $table->addColumn('className', Type::STRING);
            $table->addColumn('executedAt', Type::DATETIME);
            $table->addColumn('version', Type::DATETIME);
        }
    }
}