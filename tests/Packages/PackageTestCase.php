<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Application\Utilities\HandlerResponse\Response;
use App\Packages\Common\Application\Utilities\HandlerResponse\Success;
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
        return $this->serviceAdapter->getAuthUserFactory()->createSystemUser();
    }

    protected function getCommandBus(): CommandBus
    {
        return $this->serviceAdapter->getCommandBus();
    }

    protected function assertSuccessResponse(Response $response): void
    {
        self::assertInstanceOf(Success::class, $response, 'HandlerResponse is not an instance of Success: ' . print_r($response, true));
    }
}
