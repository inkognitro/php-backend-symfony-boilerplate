<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\Query;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Application\Query\AndX;
use App\Packages\Common\Application\Query\Condition;
use App\Packages\Common\Application\Query\Equals;
use App\Packages\Common\Application\Query\Like;
use App\Packages\Common\Application\Query\NotLike;
use App\Packages\Common\Application\Query\NotNull;
use App\Packages\Common\Application\Query\OrderBy;
use App\Packages\Common\Application\Query\OrX;
use App\Packages\Common\Application\Query\Pagination;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalQueryBuilderFactory
{
    private $connection;
    private $expressionBuilder;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
        $this->expressionBuilder = $connection->getExpressionBuilder();
    }

    public function addCondition(QueryBuilder $queryBuilder, Condition $condition, DbalEntitySettings $entitySettings): void
    {
        $dbalCondition = $this->createDbalCondition($condition, $entitySettings);
        $queryBuilder->andWhere($dbalCondition->getQueryBuilderCondition());
        $queryBuilder->setParameters(
            $dbalCondition->getParameters()->toQueryBuilderParameters(),
            $dbalCondition->getParameters()->toQueryBuilderParameterTypes()
        );
    }

    public function addPagination(QueryBuilder $queryBuilder, Pagination $pagination): void
    {
        $offset = ($pagination->getPerPage() * $pagination->getCurrentPage() - 1);
        $limit = $pagination->getPerPage();
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($limit);
    }

    public function addOrderBy(QueryBuilder $queryBuilder, OrderBy $orderBy, DbalEntitySettings $entitySettings): void
    {
        foreach ($orderBy->toArray() as $orderByAttribute) {
            $field = $entitySettings->getFieldByAttribute($orderByAttribute->getAttribute());
            $queryBuilder->addOrderBy($field, $orderByAttribute->getOrderDirection());
        }
    }

    private function createDbalCondition(Condition $condition, DbalEntitySettings $entitySettings): DbalCondition
    {
        if ($condition instanceof OrX) {
            $orX = [];
            $orXParameters = new DbalParameters([]);
            foreach ($condition->getConditions()->toArray() as $subCondition) {
                $subDbalCondition = $this->createDbalCondition($subCondition, $entitySettings);
                $orX[] = $subDbalCondition->getQueryBuilderCondition();
                $orXParameters = $orXParameters->merge($subDbalCondition->getParameters());
            }
            $sql = $this->expressionBuilder->orX(...$orX);
            return new DbalCondition($sql, $orXParameters);
        }

        if ($condition instanceof AndX) {
            $andX = [];
            $andXParameters = new DbalParameters([]);
            foreach ($condition->getConditions()->toArray() as $subCondition) {
                $subDbalCondition = $this->createDbalCondition($subCondition, $entitySettings);
                $andX[] = $subDbalCondition->getQueryBuilderCondition();
                $andXParameters = $andXParameters->merge($subDbalCondition->getParameters());
            }
            $sql = $this->expressionBuilder->andX(...$andX);
            return new DbalCondition($sql, $andXParameters);
        }

        if ($condition instanceof Like) {
            $field = $entitySettings->getFieldByAttribute($condition->getAttribute());
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->like($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof NotLike) {
            $field = $entitySettings->getFieldByAttribute($condition->getAttribute());
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->notLike($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof Equals) {
            $field = $entitySettings->getFieldByAttribute($condition->getAttribute());
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->eq($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof NotNull) {
            $field = $entitySettings->getFieldByAttribute($condition->getAttribute());
            $sql = $this->expressionBuilder->isNotNull($field);
            return new DbalCondition($sql, new DbalParameters([]));
        }

        throw new \LogicException('Condition "' . get_class($condition) . '" not supported!');
    }
}