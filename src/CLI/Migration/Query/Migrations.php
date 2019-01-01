<?php declare(strict_types=1);

namespace App\CLI\Migration\Query;

use App\Packages\Common\Installation\Migration\AbstractMigration;

final class Migrations
{
    private $migrations;

    /** @param $migrations AbstractMigration[] */
    public function __construct(array $migrations)
    {
        $this->migrations = $migrations;
    }

    /** @return AbstractMigration[] */
    public function toCollection(): array
    {
        return $this->migrations;
    }

    /** @return AbstractMigration[] */
    public function getAllByVersion(int $version): array
    {
        $migrations = [];
        foreach($this->migrations as $migration) {
            if($migration->getVersion() === $version) {
                $migrations[] = $migration;
            }
        }
        return $migrations;
    }

    public function getLatestVersion(): int
    {
        $version = 0;
        foreach($this->migrations as $migration) {
            if($migration->getVersion() > $version) {
                $version = $migration->getVersion();
            }
        }
        return $version;
    }

    public function has(AbstractMigration $migrationToFind): bool
    {
        foreach($this->migrations as $migration) {
            if(get_class($migration) === get_class($migrationToFind)) {
                return true;
            }
        }
        return false;
    }
}