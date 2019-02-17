<?php declare(strict_types=1);

namespace App\Tests;

use Doctrine\DBAL\Connection;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class TestCase extends KernelTestCase
{
    /** @var ServiceAdapter */
    private $serviceAdapter;
    /** @var string */
    private $transactionSavepointName;

    protected function getConnection(): Connection
    {
        return $this->serviceAdapter->getConnection();
    }

    protected function getContainer(): ContainerInterface
    {
        return self::$kernel->getContainer();
    }

    protected function setUp()
    {
        self::bootKernel();
        $this->transactionSavepointName = 'a' . str_replace('-', '', Uuid::uuid4()->toString());
        $this->serviceAdapter = $this->getContainer()->get(ServiceAdapter::class);
        $this->serviceAdapter->getStateManager()->beginTransaction($this->transactionSavepointName);
    }

    protected function tearDown()
    {
        $this->serviceAdapter->getStateManager()->rollbackTransaction($this->transactionSavepointName);
        parent::tearDown();
    }
}
