<?php declare(strict_types=1);

namespace App\CLI\Fixtures;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Installation\Fixtures\Fixtures;
use App\Packages\UserManagement\Installation\InstallationManager as UserManagementInstallationManager;

final class FixtureRepository
{
    private $commandBus;
    private $authUserFactory;

    public function __construct(CommandBus $commandBus, AuthUserFactory $authUserFactory)
    {
        $this->commandBus = $commandBus;
        $this->authUserFactory = $authUserFactory;
    }

    public function findAll(): Fixtures
    {
        $userMgmntInstallationManager = new UserManagementInstallationManager();
        $fixtures = $userMgmntInstallationManager->findProdFixtures($this->commandBus, $this->authUserFactory);
        if (getenv('APP_ENV') === 'dev') {
            $devFixtures = $userMgmntInstallationManager->findDevFixtures($this->commandBus, $this->authUserFactory);
            $fixtures = $fixtures->merge($devFixtures);
        }
        return $fixtures;
    }
}