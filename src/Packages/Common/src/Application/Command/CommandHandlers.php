<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Command;

final class CommandHandlers
{
    private $commandHandlers;

    private function __construct(array $commandHandlers)
    {
        $this->commandHandlers = $commandHandlers;
    }

    public function toCollection(): array
    {
        return $this->commandHandlers;
    }
}