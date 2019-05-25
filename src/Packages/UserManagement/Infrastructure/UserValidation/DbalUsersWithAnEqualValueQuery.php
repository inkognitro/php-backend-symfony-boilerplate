<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\UserValidation;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Domain\UserValidation\User;
use App\Packages\UserManagement\Domain\UserValidation\Users;
use App\Packages\UserManagement\Domain\UserValidation\UsersWithAnEqualValueQuery;
use App\Resources\User\EmailAddress;
use App\Resources\User\UserId;
use App\Resources\User\Username;

final class DbalUsersWithAnEqualValueQuery implements UsersWithAnEqualValueQuery
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('username');
        $queryBuilder->addSelect('email_address as emailAddress');
        $queryBuilder->from('users');
        $expressionBuilder = $queryBuilder->expr();
        $orX = $expressionBuilder->orX(
            $expressionBuilder->like('id', $queryBuilder->createNamedParameter($userId->toString())),
            $expressionBuilder->like('username', $queryBuilder->createNamedParameter($username->toString())),
            $expressionBuilder->like('email_address', $queryBuilder->createNamedParameter($emailAddress->toString()))
        );
        $queryBuilder->andWhere($orX);
        $users = array_map(function(array $row): User {
            return new User(
                UserId::fromString($row['id']),
                Username::fromString($row['username']),
                EmailAddress::fromString($row['emailAddress'])
            );
        }, $queryBuilder->execute()->fetchAll());
        return new Users($users);
    }
}