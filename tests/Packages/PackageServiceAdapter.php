<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\AccessManagement\Application\Query\AuthUserFactory;

final class PackageServiceAdapter
{
    protected $commandBus;
    protected $authUserFactory;

    public function __construct(CommandBus $commandBus, AuthUserFactory $authUserFactory)
    {
        $this->commandBus = $commandBus;
        $this->authUserFactory = $authUserFactory;
    }

    public function getCommandBus(): CommandBus
    {
        return $this->commandBus;
    }

    public function getAuthUserFactory(): AuthUserFactory
    {
        return $this->authUserFactory;
    }
}
