<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Application\Utilities\HandlerResponse\Response;
use App\Packages\Common\Application\Utilities\HandlerResponse\Success;
use App\Packages\Common\Application\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Tests\TestManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class PackageTestCase extends KernelTestCase
{
    /** @var $testManager TestManager */
    private $testManager;

    protected function setUp()
    {
        parent::setUp();
        static::bootKernel();
        $this->testManager = self::$kernel->getContainer()->get(TestManager::class);
        $this->testManager->setUp();
    }

    protected function tearDown()
    {
        $this->testManager->tearDown();
        parent::tearDown();
    }

    protected function createSystemAuthUser(): AuthUser
    {
        return AuthUser::system(LanguageId::english());
    }

    protected function getCommandBus(): CommandBus
    {
        return $this->testManager->getCommandBus();
    }

    protected function getUsersQueryHandler(): UsersQueryHandler
    {
        return $this->testManager->getUsersQueryHandler();
    }

    protected function assertSuccessResponse(Response $response): void
    {
        self::assertInstanceOf(Success::class, $response, 'Response: ' . "\n" . print_r($response, true));
    }

    protected function assertValidationErrorResponse(Response $response, array $expectedFieldErrors): void
    {
        self::assertInstanceOf(ValidationErrorResponse::class, $response, 'Response: ' . "\n" . print_r($response, true));
        /** @var $response ValidationErrorResponse*/
        self::assertEquals($expectedFieldErrors, $response->getFieldErrors()->toArray());
    }
}
