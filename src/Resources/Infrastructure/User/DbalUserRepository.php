<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Resources\Application\User\User;
use App\Resources\Application\User\UserRepository;

final class DbalUserRepository implements UserRepository
{
    public function findById(string $id): ?User
    {
        return null; //todo
    }

    public function save(User $user): void
    {
        //todo
    }
}