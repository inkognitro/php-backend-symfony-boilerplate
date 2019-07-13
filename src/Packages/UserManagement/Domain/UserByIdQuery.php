<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain;

use App\Resources\Application\User\Attributes\UserId;

interface UserByIdQuery
{
    public function execute(UserId $userId): ?User;
}