<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\Query;

final class DbalCondition
{
    private $queryBuilderCondition;
    private $parameters;

    public function __construct($queryBuilderCondition, DbalParameters $parameters)
    {
        $this->queryBuilderCondition = $queryBuilderCondition;
        $this->parameters = $parameters;
    }

    public function getQueryBuilderCondition()
    {
        return $this->queryBuilderCondition;
    }

    public function getParameters(): DbalParameters
    {
        return $this->parameters;
    }
}