<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Query;

use App\Packages\Common\Application\Query\AndX;

abstract class Query
{
    private $attributes;
    private $condition;
    private $orderBy;
    private $pagination;

    /** @param $attributes String[] */
    private function __construct(array $attributes, ?Condition $condition, OrderBy $orderBy, ?Pagination $pagination)
    {
        $this->attributes = $attributes;
        $this->condition = $condition;
        $this->orderBy = $orderBy;
        $this->pagination = $pagination;
    }

    private function changeByArray(array $data): self
    {
        return new static(
            ($data['attributes'] ?? $this->attributes),
            ($data['condition'] ?? $this->condition),
            ($data['orderBy'] ?? $this->orderBy),
            ($data['pagination'] ?? $this->pagination)
        );
    }

    /** @param $attributes String[] */
    public static function create(array $attributes): self
    {
        return new static($attributes, null, new OrderBy([]), null);
    }

    public function addOrderBy(string $attribute, string $direction = OrderByAttribute::ASC): self
    {
        return $this->changeByArray([
            'orderBy' => $this->orderBy->addAttribute($attribute, $direction),
        ]);
    }

    public function addPagination(int $currentPage, int $perPage = Pagination::DEFAULT_PER_PAGE): self
    {
        return $this->changeByArray([
            'pagination' => new Pagination($currentPage, $perPage)
        ]);
    }

    public function andWhere(Condition $condition): self
    {
        if ($this->condition === null) {
            return $this->changeByArray(['condition' => $condition]);
        }
        if ($this->condition instanceof AndX && $condition instanceof AndX) {
            return $this->changeByArray(['condition' => $this->condition->merge($condition)]);
        }
        if ($this->condition instanceof AndX) {
            return $this->changeByArray(['condition' => $this->condition->addCondition($condition)]);
        }
        return $this->changeByArray(['condition' => new AndX(new Conditions([$condition]))]);
    }

    /** @return String[] */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getCondition(): ?Condition
    {
        return $this->condition;
    }

    public function getOrderBy(): OrderBy
    {
        return $this->orderBy;
    }

    public function getPagination(): ?Pagination
    {
        return $this->pagination;
    }
}