<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Migration;

use App\Packages\Common\Installation\Migration\AbstractMigration;
use Doctrine\DBAL\Types\Type;

final class UsersMigration20190101174900 extends AbstractMigration
{
    public function getVersion(): int
    {
        return 1;
    }

    public function up(): void
    {
        $schema = $this->connection->getSchemaManager()->createSchema();
        $table = $schema->createTable('users');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('username', Type::STRING, ['length' => 32]);
        $table->addColumn('emailAddress', Type::STRING, ['length' => 254]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username']);
        $table->addUniqueIndex(['emailAddress']);
    }

    public function down(): void
    {
        $schema = $this->connection->getSchemaManager()->createSchema();
        $schema->dropTable('users');
    }
}