<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\Users;
use App\Packages\UserManagement\Domain\UserValidation\UsersWithAnEqualValueQuery;
use App\Packages\UserManagement\Application\Query\User\Attributes\EmailAddress;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;

final class DbalUsersWithAnEqualValueQuery implements UsersWithAnEqualValueQuery
{
    private $queryBuilderFactory;
    private $usersFactory;

    public function __construct(DbalUsersQueryBuilderFactory $queryBuilderFactory, DbalUsersFactory $usersFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->usersFactory = $usersFactory;
    }

    public function execute(UserId $userId, Username $username, EmailAddress $emailAddress): Users
    {
        $queryBuilder = $this->queryBuilderFactory->createQueryBuilder();
        $orX = $queryBuilder->expr()->orX(
            $queryBuilder->expr()->like('id', $queryBuilder->createNamedParameter($userId->toString())),
            $queryBuilder->expr()->like('username', $queryBuilder->createNamedParameter($username->toString())),
            $queryBuilder->expr()->like('email_address', $queryBuilder->createNamedParameter($emailAddress->toString()))
        );
        $queryBuilder->andWhere($orX);
        $rows = $queryBuilder->execute()->fetchAll();
        return $this->usersFactory->createFromRows($rows);
    }
}