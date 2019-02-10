<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Command\Command;
use App\Packages\Common\Application\CommandBus;
use App\Tests\TestCase;

abstract class PackageTestCase extends TestCase
{
    protected function createSystemAuthUser(): AuthUser
    {
        $userId = null;
        $role = AuthUser::SYSTEM_USER_ROLE;
        $languageId = 'en';
        return new AuthUser($userId, $role, $languageId);
    }

    protected function executeCommand(Command $command, AuthUser $authUser): void
    {
        /** @var $commandBus CommandBus */
        $commandBus = $this->container->get(CommandBus::class);
        $commandBus->handle($command, $authUser);
    }
}
