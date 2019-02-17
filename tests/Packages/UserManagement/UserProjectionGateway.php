<?php declare(strict_types=1);

namespace App\Tests\Packages\UserManagement;

use Doctrine\DBAL\Connection;

final class UserProjectionGateway
{
    private $connection;
    public const ID_FIELD = 'id';
    public const USERNAME_FIELD = 'username';
    public const EMAIL_ADDRESS_FIELD = 'email_address';
    public const PASSWORD_FIELD = 'password';
    public const ROLE_FIELD = 'role';
    public const VERIFICATION_CODE_FIELD = 'verification_code';
    public const VERIFICATION_CODE_SENT_AT_FIELD = 'verification_code_sent_at';
    public const VERIFIED_AT_FIELD = 'verified_at';
    public const CREATED_AT_FIELD = 'created_at';
    public const UPDATED_AT_FIELD = 'updated_at';

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getRowById(string $userId): ?array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect(self::ID_FIELD);
        $queryBuilder->addSelect(self::USERNAME_FIELD);
        $queryBuilder->addSelect(self::EMAIL_ADDRESS_FIELD);
        $queryBuilder->addSelect(self::PASSWORD_FIELD);
        $queryBuilder->addSelect(self::ROLE_FIELD);
        $queryBuilder->addSelect(self::VERIFICATION_CODE_FIELD);
        $queryBuilder->addSelect(self::VERIFICATION_CODE_SENT_AT_FIELD);
        $queryBuilder->addSelect(self::VERIFIED_AT_FIELD);
        $queryBuilder->addSelect(self::CREATED_AT_FIELD);
        $queryBuilder->addSelect(self::UPDATED_AT_FIELD);
        $queryBuilder->from('users');
        $queryBuilder->andWhere(self::ID_FIELD . " = {$queryBuilder->createNamedParameter($userId)}");
        $row = $queryBuilder->execute()->fetch();
        if(!$row) {
            return null;
        }
        return $row;
    }
}
