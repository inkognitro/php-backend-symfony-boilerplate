<?php declare(strict_types=1);

namespace App\CLI\Migration\Query;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Installation\Migration\UsersMigration20190101174900;
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

    public function getAllRegistered(): Migrations
    {
        $migrations = [];
        foreach(self::ORDERED_MIGRATIONS as $migrationClassName) {
            $migrations[] = new $migrationClassName($this->connection);
        }
        return new Migrations($migrations);
    }

    public function getAllExecuted(): Migrations
    {
        $schema = $this->connection->getSchemaManager()->createSchema();
        try {
            $schema->getTable('migrations');
        } catch (SchemaException $e) {
            return new Migrations([]);
        }

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('className');
        $rows = $queryBuilder->execute()->fetchAll();

        $migrations = [];
        foreach($rows as $row) {
            $migrationClassName = $row['className'];
            $migrations[] = new $migrationClassName($this->connection);
        }

        return new Migrations($migrations);
    }
}