<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migration;

use Doctrine\DBAL\Schema\Schema;

abstract class AbstractMigration
{
    public abstract function getVersion(): int;
    public abstract function up(Schema $schema): void;
    public abstract function down(Schema $schema): void;
}