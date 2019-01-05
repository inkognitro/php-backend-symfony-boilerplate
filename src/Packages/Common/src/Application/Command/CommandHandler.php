<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

interface CommandHandler
{
    public function getCommandClassName(): string;
}