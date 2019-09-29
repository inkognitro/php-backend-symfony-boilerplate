<?php declare(strict_types=1);

namespace App\Tests\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\Common\Application\Utilities\HandlerResponse\Response as HandlerResponse;
use App\Packages\Common\Application\Utilities\HandlerResponse\Success;
use App\Tests\TestManager;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as SymfonyWebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class WebTestCase extends SymfonyWebTestCase
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

    protected function assertSuccessHandlerResponse(HandlerResponse $response): void
    {
        self::assertInstanceOf(Success::class, $response, 'Response: ' . "\n" . print_r($response, true));
    }

    protected function executeGETRequest(string $url, array $params): Response
    {
        return $this->executeRequest('GET', $url, $params);
    }

    protected function executePOSTRequest(string $url, array $params): Response
    {
        return $this->executeRequest('POST', $url, $params);
    }

    private function executeRequest(string $method, string $url, array $params): Response
    {
        /** @var $client Client */
        //todo https://symfony.com/doc/current/testing.html#your-first-functional-test
        return null;
    }
}
