<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

interface UserRepository
{
    public function findById(UserId $userId): ?User;
}