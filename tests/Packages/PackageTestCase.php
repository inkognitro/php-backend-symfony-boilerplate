<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Command\Command;
use App\Packages\Common\Application\CommandBus;
use App\Tests\TestCase;

abstract class PackageTestCase extends TestCase
{
    /** @var PackageServiceAdapter */
    private $serviceAdapter;

    protected function setUp()
    {
        parent::setUp();
        $this->serviceAdapter = $this->getContainer()->get(PackageServiceAdapter::class);
    }

    protected function createSystemAuthUser(): AuthUser
    {
        $userId = null;
        $role = AuthUser::SYSTEM_USER_ROLE;
        $languageId = 'en';
        return new AuthUser($userId, $role, $languageId);
    }

    protected function getCommandBus(): CommandBus
    {
       return $this->serviceAdapter->getCommandBus();
    }
}
