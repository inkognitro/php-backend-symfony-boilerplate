<?php declare(strict_types=1);

namespace App\Resources\Infrastructure;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Utilities\Query\AndX;
use App\Utilities\Query\Condition;
use App\Utilities\Query\Equals;
use App\Utilities\Query\Like;
use App\Utilities\Query\NotLike;
use App\Utilities\Query\NotNull;
use App\Utilities\Query\OrderBy;
use App\Utilities\Query\OrX;
use App\Utilities\Query\Pagination;
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

    public function addCondition(QueryBuilder $queryBuilder, Condition $condition, array $attributeToFieldMapping): void
    {
        $dbalCondition = $this->createDbalCondition($condition, $attributeToFieldMapping);
        $queryBuilder->andWhere($dbalCondition->getQueryBuilderCondition());
        $queryBuilder->setParameters(
            $dbalCondition->getParameters()->toQueryBuilderParameters(),
            $dbalCondition->getParameters()->toQueryBuilderParameterTypes()
        );
        throw new \LogicException('Condition "' . get_class($condition) . '" not supported!');
    }

    public function addPagination(QueryBuilder $queryBuilder, Pagination $pagination): void
    {
        $offset = ($pagination->getPerPage() * $pagination->getCurrentPage() - 1);
        $limit = $pagination->getPerPage();
        $queryBuilder->setFirstResult($offset);
        $queryBuilder->setMaxResults($limit);
    }

    public function addOrderBy(QueryBuilder $queryBuilder, OrderBy $orderBy, array $attributeToFieldMapping): void
    {
        foreach ($orderBy->toArray() as $orderByAttribute) {
            $field = $attributeToFieldMapping[$orderByAttribute->getAttribute()];
            $queryBuilder->addOrderBy($field, $orderByAttribute->getOrderDirection());
        }
    }

    private function createDbalCondition(Condition $condition, array $attributeToFieldMapping): DbalCondition
    {
        if ($condition instanceof OrX) {
            $orX = [];
            $orXParameters = new DbalParameters([]);
            foreach ($condition->getConditions()->toArray() as $subCondition) {
                $subDbalCondition = $this->createDbalCondition($subCondition, $attributeToFieldMapping);
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
                $subDbalCondition = $this->createDbalCondition($subCondition, $attributeToFieldMapping);
                $andX[] = $subDbalCondition->getQueryBuilderCondition();
                $andXParameters = $andXParameters->merge($subDbalCondition->getParameters());
            }
            $sql = $this->expressionBuilder->andX(...$andX);
            return new DbalCondition($sql, $andXParameters);
        }

        if ($condition instanceof Like) {
            $field = $attributeToFieldMapping[$condition->getAttribute()];
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->like($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof NotLike) {
            $field = $attributeToFieldMapping[$condition->getAttribute()];
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->notLike($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof Equals) {
            $field = $attributeToFieldMapping[$condition->getAttribute()];
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->eq($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        if ($condition instanceof NotNull) {
            $field = $attributeToFieldMapping[$condition->getAttribute()];
            $sql = $this->expressionBuilder->isNotNull($field);
            return new DbalCondition($sql, new DbalParameters([]));
        }

        throw new \LogicException('Condition "' . get_class($condition) . '" not supported!');
    }
}