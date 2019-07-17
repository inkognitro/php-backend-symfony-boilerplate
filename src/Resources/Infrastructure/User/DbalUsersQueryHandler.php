<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Resources\Application\User\Users;
use App\Resources\Application\User\UsersQuery;
use App\Resources\Application\User\UsersQueryHandler;
use App\Resources\Infrastructure\DbalQueryBuilderFactory;

final class DbalUsersQueryHandler implements UsersQueryHandler
{
    private const ATTRIBUTE_TO_FIELD_MAPPING = [

    ];

    private $connection;
    private $queryBuilderFactory;
    private $usersFactory;

    public function __construct(DbalConnection $connection, DbalQueryBuilderFactory $queryBuilderFactory, UsersFactory $usersFactory)
    {
        $this->connection = $connection;
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->usersFactory = $usersFactory;
    }

    public function handle(UsersQuery $query): Users
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        foreach ($query->getAttributes() as $attribute) {
            $field = self::ATTRIBUTE_TO_FIELD_MAPPING[$attribute];
            $queryBuilder->addSelect($field);
        }
        $queryBuilder->from('users');
        if ($query->getCondition() !== null) {
            $this->queryBuilderFactory->addCondition($queryBuilder, $query->getCondition(), self::ATTRIBUTE_TO_FIELD_MAPPING);
        }
        if ($query->getPagination() !== null) {
            $this->queryBuilderFactory->addPagination($queryBuilder, $query->getPagination());
        }
        $this->queryBuilderFactory->addOrderBy($queryBuilder, $query->getOrderBy(), self::ATTRIBUTE_TO_FIELD_MAPPING);
        $rows = $queryBuilder->execute()->fetchAll();
        return $this->usersFactory->createFromRows($rows);
    }
}