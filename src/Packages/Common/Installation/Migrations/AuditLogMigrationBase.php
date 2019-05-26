<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class AuditLogMigrationBase extends Migration
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
        $table->addColumn('event', Type::STRING, ['length' => 128]);
        $table->addColumn('resource_type', Type::STRING, ['length' => 128, 'notnull' => false]);
        $table->addColumn('resource_id', Type::GUID, ['notnull' => false]);
        $table->addColumn('payload', Type::TEXT);
        $table->addColumn('auth_user_payload', Type::TEXT);
        $table->addColumn('occurred_at', Type::DATETIME);
        $table->setPrimaryKey(['sequence_number']);
        $table->addUniqueIndex(['id']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('audit_log');
    }
}