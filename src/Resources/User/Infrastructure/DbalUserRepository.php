<?php declare(strict_types=1);

namespace App\Resources\User\Infrastructure\User;

use App\Resources\User\Application\Command\CommandUser as CommandUser;
use App\Resources\User\Application\User;
use App\Resources\User\Application\UserRepository;

final class DbalUserRepository implements UserRepository
{
    public function findById(string $id): ?User
    {
        return null; //todo
    }

    public function findByEmailAddress(string $emailAddress): ?User
    {
        return null; //todo
    }

    public function save(CommandUser $user): void
    {
        //todo
    }
}