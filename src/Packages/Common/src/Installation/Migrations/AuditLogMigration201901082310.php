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

    public function getBatchSequenceNumber(): int
    {
        return 2;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('audit_log');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('sequence_number', Type::INTEGER, ['autoincrement' => true]);
        $table->addColumn('event_class_name', Type::STRING, ['length' => 128]);
        $table->addColumn('resource_class_name', Type::STRING, ['length' => 128, 'notnull' => false]);
        $table->addColumn('resource_id', Type::GUID, ['notnull' => false]);
        $table->addColumn('previous_payload', Type::TEXT, ['notnull' => false]);
        $table->addColumn('payload', Type::TEXT);
        $table->addColumn('auth_user_role', Type::STRING, ['length' => 16]);
        $table->addColumn('auth_user_id', Type::GUID, ['notnull' => false]);
        $table->addColumn('auth_user_language_id', Type::STRING, ['length' => 8]);
        $table->addColumn('occurred_at', Type::DATETIME);
        $table->setPrimaryKey(['sequence_number']);
        $table->addUniqueIndex(['id']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('audit_log');
    }
}