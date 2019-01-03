<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Schema\Schema;

abstract class AbstractMigration
{
    public abstract function getBatchNumber(): int;
    public abstract function up(Schema $schema): void;
    public abstract function down(Schema $schema): void;
}