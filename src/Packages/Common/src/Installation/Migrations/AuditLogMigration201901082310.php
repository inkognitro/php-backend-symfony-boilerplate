<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class AuditLogMigration201901082310 extends AbstractMigration
{
    public function getBatchNumber(): int
    {
        return 1;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('audit_log');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('event_class', Type::STRING, ['length' => 32]);
        $table->addColumn('previous_payload', Type::TEXT, ['notnull' => false]);
        $table->addColumn('payload', Type::TEXT);
        $table->addColumn('triggered_by_auth_user_role', Type::DATETIME);
        $table->addColumn('triggered_by_auth_user_id', Type::GUID, ['notnull' => false]);
        $table->addColumn('occurred_on', Type::DATETIME);
        $table->setPrimaryKey(['id']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('audit_log');
    }
}