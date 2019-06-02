<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Migrations;

use App\Packages\Common\Installation\Migrations\Migration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

final class UserEmailAddressVerificationCodesMigrationBase extends Migration
{
    public function getBatchNumber(): int
    {
        return 1;
    }

    public function getBatchSequenceNumber(): int
    {
        return 5;
    }

    public function schemaUp(Schema $schema): void
    {
        $table = $schema->createTable('user_email_address_verification_codes');
        $table->addColumn('user_id', Type::GUID);
        $table->addColumn('email_address', Type::STRING, ['length' => 191]);
        $table->addColumn('code', Type::STRING, ['notnull' => false, 'length' => 32]);
        $table->addColumn('code_sent_at', Type::DATETIME, ['notnull' => false]);
        $table->setPrimaryKey(['user_id', 'email_address']);
        $table->addForeignKeyConstraint('users', ['user_id'], ['id']);
    }

    public function schemaDown(Schema $schema): void
    {
        $schema->dropTable('user_email_address_verification_codes');
    }
}