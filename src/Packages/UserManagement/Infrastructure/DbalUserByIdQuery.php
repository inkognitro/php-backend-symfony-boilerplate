<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\UserManagement\Domain\User;
use App\Packages\UserManagement\Domain\UserByIdQuery;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;

final class DbalUserByIdQuery implements UserByIdQuery
{
    private $queryBuilderFactory;
    private $userFactory;

    public function __construct(DbalUsersQueryBuilderFactory $queryBuilderFactory, DbalUserFactory $userFactory)
    {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->userFactory = $userFactory;
    }

    public function execute(UserId $userId): ?User
    {
        $queryBuilder = $this->queryBuilderFactory->createQueryBuilder();
        $queryBuilder->andWhere($queryBuilder->expr()->like('id', $queryBuilder->createNamedParameter($userId->toString())));
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->userFactory->createFromRow($row);
    }
}