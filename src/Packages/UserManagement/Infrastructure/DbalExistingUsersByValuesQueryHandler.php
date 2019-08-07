<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\UserParamsValidation\Users;
use App\Packages\UserManagement\Domain\UserParamsValidation\ExistingUsersByValuesQueryHandler;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;

final class DbalExistingUsersByValuesQueryHandler implements ExistingUsersByValuesQueryHandler
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