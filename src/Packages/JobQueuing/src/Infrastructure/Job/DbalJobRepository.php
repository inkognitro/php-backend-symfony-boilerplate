<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Infrastructure\Job;

use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\UpdatedAt;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\JobQueuing\Application\Resources\Job\Job;
use App\Packages\JobQueuing\Application\Resources\Job\JobId;
use App\Packages\JobQueuing\Application\Resources\Job\JobRepository;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalJobRepository implements JobRepository
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(JobId $id): ?Job
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("id = {$queryBuilder->createNamedParameter($id->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createJob($row);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('created_at as createdAt');
        $queryBuilder->addSelect('updated_at as updatedAt');
        $queryBuilder->from('jobs');
        return $queryBuilder;
    }

    private function createJob(array $data): Job
    {
        $updatedAt = ($data['updatedAt'] === null ? null : UpdatedAt::fromString($data['updatedAt']));
        return new Job(
            JobId::fromString($data['id']),
            CreatedAt::fromString($data['createdAt']),
            $updatedAt
        );
    }
}