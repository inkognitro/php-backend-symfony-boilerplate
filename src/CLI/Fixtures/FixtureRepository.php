<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Installation\Fixtures\Fixtures;
use App\Packages\UserManagement\Installation\InstallationManager as UserManagementInstallationManager;

final class FixtureRepository
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function findAll(): Fixtures
    {
        $userMgmntInstallationManager = new UserManagementInstallationManager();
        $fixtures = $userMgmntInstallationManager->findProdFixtures($this->commandBus);
        if (getenv('APP_ENV') === 'dev') {
            $devFixtures = $userMgmntInstallationManager->findDevFixtures($this->commandBus);
            $fixtures = $fixtures->merge($devFixtures);
        }
        return $fixtures;
    }
}