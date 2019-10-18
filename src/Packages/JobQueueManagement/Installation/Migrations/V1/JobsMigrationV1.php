<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Installation\Migrations\V1;

use App\Packages\Common\Installation\Migrations\Migration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class JobsMigrationV1 extends Migration
{
    public function getBatchNumber(): int
    {
        return 1;
    }

    public function getBatchSequenceNumber(): int
    {
        return 3;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('jobs');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('command', Type::TEXT);
        $table->addColumn('created_at', Type::DATETIME);
        $table->addColumn('execution_started_at', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('execution_finalized_at', Type::DATETIME, ['notnull' => false]);
        $table->setPrimaryKey(['id']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('jobs');
    }
}