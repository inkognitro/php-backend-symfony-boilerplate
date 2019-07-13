<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Resources\Application\User\UsersQuery;
use App\Resources\Application\User\UsersQueryHandler;
use App\Resources\Infrastructure\DbalQueryBuilderFactory;

final class DbalUsersQueryHandler implements UsersQueryHandler
{
    private $connection;
    private $queryBuilderFactory;

    public function __construct(DbalConnection $connection, DbalQueryBuilderFactory $queryBuilderFactory)
    {
        $this->connection = $connection;
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function handle(UsersQuery $query): Users
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect();
    }
}