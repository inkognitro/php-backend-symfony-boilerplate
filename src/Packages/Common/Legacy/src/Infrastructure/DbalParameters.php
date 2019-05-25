<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use Doctrine\DBAL\Query\QueryBuilder;

final class DbalParameters
{
    private $parameters;

    /** @param $parameters DbalParameter[] */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function applyToQueryBuilder(QueryBuilder $queryBuilder): void
    {
        foreach($this->parameters as $parameter) {
            $queryBuilder->setParameter($parameter->getName(), $parameter->getValue(), $parameter->getType());
        }
    }

    /** @return DbalParameter[] */
    public function toArray(): array
    {
        return $this->parameters;
    }
}