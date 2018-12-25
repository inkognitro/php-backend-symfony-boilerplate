<?php declare(strict_types=1);

namespace App\Packages\Authentication\Infrastructure;

use App\Packages\Authentication\Application\UserByIdQuery;
use Doctrine\DBAL\Connection;

final class DbalUserByIdQuery implements UserByIdQuery
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(string $userId): ?array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('role');
        $queryBuilder->addSelect('languageId');
        $queryBuilder->from('users');
        $queryBuilder->andWhere("id = {$queryBuilder->createNamedParameter($userId)}");
        $row = $queryBuilder->execute()->fetch();
        if(!$row) {
            return null;
        }
        return $row;
    }
}