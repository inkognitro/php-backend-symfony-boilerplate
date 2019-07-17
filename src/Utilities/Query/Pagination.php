<?php declare(strict_types=1);

namespace App\Utilities\Query;

final class Pagination
{
    public const DEFAULT_PER_PAGE = 10;

    private $currentPage;
    private $perPage;

    public function __construct(int $currentPage, int $perPage)
    {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}