<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Migrations;

use App\Packages\Common\Installation\Migrations\Migration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class UsersMigrationBase extends Migration
{
    public function getBatchNumber(): int
    {
        return 1;
    }

    public function getBatchSequenceNumber(): int
    {
        return 4;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', Type::GUID);
        $table->addColumn('username', Type::STRING, ['length' => 32]);
        $table->addColumn('email_address', Type::STRING, ['length' => 191]);
        $table->addColumn('password_hash', Type::BINARY, ['length' => 60]);
        $table->addColumn('role_id', Type::STRING, ['length' => 32]);
        $table->addColumn('verified_at', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('jwt_reset_at', Type::DATETIME, ['notnull' => false]);
        $table->addColumn('created_at', Type::DATETIME);
        $table->addColumn('updated_at', Type::DATETIME, ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['username']);
        $table->addUniqueIndex(['email_address']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}