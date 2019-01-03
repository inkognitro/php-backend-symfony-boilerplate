<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Installation\Migrations\UsersMigration20190101174900;
use Doctrine\DBAL\Schema\SchemaException;

final class MigrationRepository
{
    private $connection;

    private const ORDERED_MIGRATIONS = [
        UsersMigration20190101174900::class,
    ];

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findAllExecuted(): Migrations
    {
        $schema = $this->connection->getSchemaManager()->createSchema();
        try {
            $schema->getTable('migrations');
        } catch (SchemaException $e) {
            return new Migrations([]);
        }

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('class_name as className');
        $queryBuilder->from('migrations');
        $rows = $queryBuilder->execute()->fetchAll();

        $migrations = [];
        foreach($rows as $row) {
            $migrationClassName = $row['className'];
            if(!in_array($migrationClassName, self::ORDERED_MIGRATIONS)) {
                continue;
            }
            $migrations[] = new $migrationClassName($this->connection);
        }

        return new Migrations($migrations);
    }

    public function findAllNotExecuted(): Migrations
    {
        $executedMigrations = $this->findAllExecuted();
        $migrations = [];
        foreach(self::ORDERED_MIGRATIONS as $migrationClassName) {
            $migration = new $migrationClassName();
            if(!$executedMigrations->has($migration)) {
                $migrations[] = $migration;
            }
        }
        return new Migrations($migrations);
    }
}