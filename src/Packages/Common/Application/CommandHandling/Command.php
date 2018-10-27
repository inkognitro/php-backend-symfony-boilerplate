<?php declare(strict_types=1);

namespace App\Packages\Common\Application\CommandHandling;

use App\Packages\Common\Application\Authorization\Permission;

interface Command
{
    public function getPermission(): ?Permission;
}