<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

abstract class AbstractMigration
{
    public abstract function getBatchNumber(): int;
    public abstract function getBatchSequenceNumber(): int;

    public abstract function schemaUp(Schema $schema): void;
    public abstract function schemaDown(Schema $schema): void;

    public function dataMigration(Connection $schema): void
    {

    }

    public function dataRollback(Connection $schema): void
    {

    }

    public function schemaUpAfterDataMigration(Schema $schema): void
    {

    }

    public function schemaDownBeforeDataRollback(Schema $schema): void
    {

    }
}