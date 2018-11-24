<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

final class QuerySettings
{
    private $pageNumber;
    private $perPage;
    private $ordersBy;

    public function __construct(int $pageNumber, int $perPage, array $ordersBy)
    {
        $this->pageNumber = $pageNumber;
        $this->perPage = $perPage;
        $this->ordersBy = $ordersBy;
    }

    public function getOrdersBy(): array
    {
        return $this->ordersBy;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }
    
    public function getPerPage(): int
    {
        return $this->perPage;
    }
}