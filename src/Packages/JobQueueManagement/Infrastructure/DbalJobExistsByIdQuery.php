<?php declare(strict_types=1);

namespace App\Packages\JobQueueManagement\Infrastructure;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\JobQueueManagement\Domain\JobValidation\JobExistsByIdQuery;
use App\Packages\JobQueueManagement\Application\ResourceAttributes\Job\QueueJobId;

final class DbalJobExistsByIdQuery implements JobExistsByIdQuery
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function execute(QueueJobId $jobId): bool
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->select('id');
        $queryBuilder->from('jobs');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->like('id', $queryBuilder->createNamedParameter($jobId->toString()))
        );
        return (bool)$queryBuilder->execute()->fetch();
    }
}