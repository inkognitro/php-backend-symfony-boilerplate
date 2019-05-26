<?php declare(strict_types=1);

namespace App\Packages\Common\Installation\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class MigrationsMigrationBase extends Migration
{
    public function getBatchNumber(): int
    {
        return 1;
    }

    public function getBatchSequenceNumber(): int
    {
        return 1;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('migrations');
        $table->addColumn('class_name', Type::STRING, ['length' => 191]);
        $table->addColumn('batch_number', Type::INTEGER);
        $table->addColumn('batch_sequence_number', Type::INTEGER);
        $table->addColumn('executed_at', Type::DATETIME);
        $table->setPrimaryKey(['class_name']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('migrations');
    }
}