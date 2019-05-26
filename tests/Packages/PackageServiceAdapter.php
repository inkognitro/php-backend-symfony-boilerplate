<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\Common\Application\CommandBus;

final class PackageServiceAdapter
{
    protected $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }
}
