<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Resources\User\UserId;

interface UserByIdQuery
{
    public function execute(UserId $userId): ?User;
}