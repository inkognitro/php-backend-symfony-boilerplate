<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\Common\Infrastructure\DbalConnection;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalUsersQueryBuilderFactory
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('username');
        $queryBuilder->addSelect('email_address as emailAddress');
        $queryBuilder->from('users');
        return $queryBuilder;
    }
}