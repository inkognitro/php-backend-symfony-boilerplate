<?php declare(strict_types=1);

namespace App\Utilities\Query;

final class OrderByAttribute
{
    public const DESC = 'desc';
    public const ASC = 'asc';

    private $attribute;
    private $orderDirection;

    public function __construct(string $attribute, string $orderDirection)
    {
        $this->attribute = $attribute;
        $this->orderDirection = $orderDirection;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }
}