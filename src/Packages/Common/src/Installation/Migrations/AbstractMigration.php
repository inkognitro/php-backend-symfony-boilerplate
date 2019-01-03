<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

abstract class AbstractMigration
{
    public abstract function getBatchNumber(): int;
    public abstract function schemaUp(Schema $schema): void;
    public abstract function schemaDown(Schema $schema): void;

    public function dataUpBefore(Connection $schema): void
    {

    }

    public function dataUpAfter(Connection $schema): void
    {

    }

    public function dataDownBefore(Connection $schema): void
    {

    }

    public function dataDownAfter(Connection $schema): void
    {

    }
}