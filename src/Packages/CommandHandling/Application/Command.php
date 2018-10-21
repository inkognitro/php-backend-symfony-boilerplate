<?php declare(strict_types=1);

namespace App\Packages\Authentication\Application;

interface Command
{
    public function getPermission(string $userId): ?Permission;
}