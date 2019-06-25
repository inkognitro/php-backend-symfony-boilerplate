<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authentication;

interface UserQueryHandler
{
    public function handle(UserQuery $query): ?User;
}