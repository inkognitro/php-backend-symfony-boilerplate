<?php declare(strict_types=1);

namespace App\CLI\Migrations;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Installation\InstallationManager as CommonInstallationManager;
use App\Packages\JobQueueManagement\Installation\InstallationManager as JobQueueManagementInstallationManager;
use App\Packages\UserManagement\Installation\InstallationManager as UserManagementInstallationManager;
use App\Packages\Common\Installation\Migrations\Migration;
use App\Packages\Common\Installation\Migrations\Migrations;
use Doctrine\DBAL\Schema\SchemaException;

final class MigrationRepository
{
    private $migrations;
    private $connection;
    
    public function __construct(DbalConnection $connection)
    {
        $migrations = (new CommonInstallationManager())->findMigrations();
        $migrations = $migrations->merge((new JobQueueManagementInstallationManager())->findMigrations());
        $migrations = $migrations->merge((new UserManagementInstallationManager())->findMigrations());
        $this->migrations = $migrations;
        $this->connection = $connection;
    }

    private function findMigrationByClassName(string $className): ?Migration
    {
        foreach($this->migrations->toArray() as $migration) {
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
        foreach($this->migrations->toArray() as $migration) {
            if(!$executedMigrations->has($migration)) {
                $migrations[] = $migration;
            }
        }
        return new Migrations($migrations);
    }
}