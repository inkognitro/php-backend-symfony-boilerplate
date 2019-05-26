<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use Doctrine\DBAL\Schema\SchemaException;

final class MigrationRepository
{
    private $migrations;
    private $connection;
    
    public function __construct(iterable $migrations, DbalConnection $connection)
    {
        $this->migrations = self::getIterableAsArray($migrations);
        usort($this->migrations, [$this, 'compareMigrations']);
        $this->connection = $connection;
    }

    private function compareMigrations(Migration $a, Migration $b): int
    {
        if($a->getBatchNumber() < $b->getBatchNumber()) {
            return -1;
        }

        if($a->getBatchNumber() > $b->getBatchNumber()) {
            return 1;
        }

        if($a->getBatchSequenceNumber() < $b->getBatchSequenceNumber()) {
            return -1;
        }

        if($a->getBatchSequenceNumber() > $b->getBatchSequenceNumber()) {
            return 1;
        }

        return 0;
    }

    private static function getIterableAsArray(iterable $migrations): array
    {
        $migrationsAsArray = [];
        foreach($migrations as $migration) {
            $migrationsAsArray[] = $migration;
        }
        return $migrationsAsArray;
    }

    private function findMigrationByClassName(string $className): ?Migration
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
        $queryBuilder->addOrderBy('batch_number');
        $queryBuilder->addOrderBy('batch_sequence_number');
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