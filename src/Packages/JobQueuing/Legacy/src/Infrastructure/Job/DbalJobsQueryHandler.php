<?php declare(strict_types=1);

namespace App\Packages\JobQueuing\Infrastructure\Job;

use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\Job\Attributes\Values\ExecutedAt;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\JobQueuing\Domain\Job\Attributes\Values\JobId;
use App\Packages\JobQueuing\Domain\Job\Job;
use App\Packages\JobQueuing\Domain\Job\Jobs;
use App\Packages\JobQueuing\Domain\Job\JobsQuery;
use App\Packages\JobQueuing\Domain\Job\JobsQueryHandler;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalJobsQueryHandler implements JobsQueryHandler
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(JobsQuery $jobsQuery): Jobs
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("id = {$queryBuilder->createNamedParameter($id->toString())}");
        $rows = $queryBuilder->execute()->fetchAll();
        $jobs = [];
        foreach($rows as $row) {
            $jobs[] = $this->createJob($row);
        }
        return new Jobs($jobs);
    }

    private function createJob(array $row): Job
    {
        $command = json_decode($row['command']);
        $executedAt = ($row['executedAt'] === null ? null : ExecutedAt::fromString($row['executedAt']));
        return new Job(
            JobId::fromString($row['id']),
            $command,
            CreatedAt::fromString($row['createdAt']),
            $executedAt
        );
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('command');
        $queryBuilder->addSelect('created_at as createdAt');
        $queryBuilder->addSelect('executed_at as executedAt');
        $queryBuilder->from('jobs');
        return $queryBuilder;
    }
}