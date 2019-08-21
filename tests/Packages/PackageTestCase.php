<?php declare(strict_types=1);

namespace App\Tests\Packages;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Application\Utilities\HandlerResponse\Response;
use App\Packages\Common\Application\Utilities\HandlerResponse\Success;
use App\Packages\Common\Application\Utilities\HandlerResponse\ValidationErrorResponse;
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
        return AuthUser::system(LanguageId::english());
    }

    protected function getCommandBus(): CommandBus
    {
        return $this->serviceAdapter->getCommandBus();
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
