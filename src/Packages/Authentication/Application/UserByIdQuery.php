<?php declare(strict_types=1);

namespace App\Packages\Authentication\Application;

interface UserByIdQuery
{
    public function execute(string $userId): ?array;
}