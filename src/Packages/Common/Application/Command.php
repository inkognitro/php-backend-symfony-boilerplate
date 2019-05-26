<?php declare(strict_types=1);

namespace App\Packages\Common\Application;

use App\Utilities\AuthUser;

interface Command
{
    public static function getHandlerClass(): string;
    public function getExecutor(): AuthUser;
}