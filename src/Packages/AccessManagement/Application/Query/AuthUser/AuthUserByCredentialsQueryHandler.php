<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query\AuthUser;

interface AuthUserByCredentialsQueryHandler
{
    public function handle(AuthUserByCredentialsQuery $query): ?AuthUser;
}