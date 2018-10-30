<?php declare(strict_types=1);

namespace App\Resources\Application\User;

interface UserRepository
{
    public function findById(string $id): ?User;
    public function save(User $user): void;
}