<?php declare(strict_types=1);

namespace App\Resources\Infrastructure;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Utilities\Query\AndX;
use App\Utilities\Query\Condition;
use App\Utilities\Query\Equals;
use App\Utilities\Query\Like;
use App\Utilities\Query\OrX;
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

        if ($condition instanceof Equals) {
            $field = $attributeToFieldMapping[$condition->getAttribute()];
            $parameter = DbalParameter::create($condition->getValue());
            $sql = $this->expressionBuilder->eq($field, ':' . $parameter->getName());
            return new DbalCondition($sql, new DbalParameters([$parameter]));
        }

        throw new \LogicException('Condition "' . get_class($condition) . '" not supported!');
    }
}