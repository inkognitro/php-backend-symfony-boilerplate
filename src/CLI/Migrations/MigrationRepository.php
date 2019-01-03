<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\SchemaException;

final class MigrationRepository
{
    private $migrations;
    private $connection;

    public function __construct(iterable $migrations, DbalConnection $connection)
    {
        $this->migrations = self::getIterableAsArray($migrations);
        $this->connection = $connection;
    }

    private static function getIterableAsArray(iterable $migrations): array
    {
        $migrationsAsArray = [];
        foreach($migrations as $migration) {
            $migrationsAsArray[] = $migration;
        }
        return $migrationsAsArray;
    }

    private function findMigrationByClassName(string $className): ?AbstractMigration
    {
        foreach($this->migrations as $migration) {
            if(get_class($migration) === $className) {
                return $migration;
            }
        }
        return null;
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
            $migration = $this->findMigrationByClassName($row['className']);
            if($migration !== null) {
                $migrations[] = new $migrationClassName($this->connection);
            }
        }

        return new Migrations($migrations);
    }

    public function findAllNotExecuted(): Migrations
    {
        $executedMigrations = $this->findAllExecuted();
        $migrations = [];
        foreach($this->migrations as $migration) {
            if(!$executedMigrations->has($migration)) {
                $migrations[] = $migration;
            }
        }
        return new Migrations($migrations);
    }
}