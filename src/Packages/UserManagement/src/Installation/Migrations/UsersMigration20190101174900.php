<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Migrations;

use App\Packages\Common\Installation\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class UsersMigration20190101174900 extends AbstractMigration
{
    public function getVersion(): int
    {
        return 1;
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('username', Type::STRING, ['length' => 32]);
        $table->addColumn('emailAddress', Type::STRING, ['length' => 254]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username']);
        $table->addUniqueIndex(['emailAddress']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}