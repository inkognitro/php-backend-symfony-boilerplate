<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

interface Command
{
    public static function getHandlerClass(): string;
    public function getExecutor(): AuthUser;
}