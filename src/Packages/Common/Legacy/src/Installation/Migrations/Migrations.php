<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

final class Migrations
{
    private $migrations;

    /** @param $migrations AbstractMigration[] */
    public function __construct(array $migrations)
    {
        $this->migrations = $migrations;
    }

    /** @return AbstractMigration[] */
    public function toArray(): array
    {
        return $this->migrations;
    }

    public function findAllWithHigherBatchNumber(int $batchNumber): self
    {
        $migrations = [];
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() > $batchNumber) {
                $migrations[] = $migration;
            }
        }
        return new self($migrations);
    }

    public function getHighestBatchNumber(): int
    {
        $batchNumber = (count($this->migrations) === 0 ? 0 : $this->migrations[0]->getBatchNumber());
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() > $batchNumber) {
                $batchNumber = $migration->getBatchNumber();
            }
        }
        return $batchNumber;
    }

    public function getLowestBatchNumber(): int
    {
        $batchNumber = (count($this->migrations) === 0 ? 0 : $this->migrations[0]->getBatchNumber());
        foreach($this->migrations as $migration) {
            if($migration->getBatchNumber() < $batchNumber) {
                $batchNumber = $migration->getBatchNumber();
            }
        }
        return $batchNumber;
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