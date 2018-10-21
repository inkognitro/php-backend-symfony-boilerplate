<?php declare(strict_types=1);

namespace App\Packages\Authentication\Infrastructure;

use App\Packages\Authentication\Application\UserByIdQuery;

final class DbalUserByIdQuery implements UserByIdQuery
{
    public function execute(string $userId): ?array
    {
        return [
            'id' => $userId,
            'role' => 'user'
        ];
    }
}