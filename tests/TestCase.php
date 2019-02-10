<?php declare(strict_types=1);

namespace App\Tests;

use App\Packages\Common\Infrastructure\DbalConnection;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class TestCase extends PhpUnitTestCase
{
    /** @var ContainerInterface */
    protected $container;
    /** @var Connection */
    protected $connection;
    /** @var string */
    protected $transactionSavepointName;

    public function __construct($name = null, array $data = [], $dataName = '', ContainerInterface $container)
    {
        parent::__construct($name, $data, $dataName);
        $this->container = $container;
        $this->connection = $container->get(DbalConnection::class);
        $this->transactionSavepointName = 'a' . str_replace('-', '', Uuid::uuid4()->toString());
    }

    protected function getConnection(): Connection
    {
        return $this->connection;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->connection->beginTransaction();
        $this->connection->createSavepoint($this->transactionSavepointName);
    }

    protected function tearDown()
    {
        $this->connection->rollbackSavepoint($this->transactionSavepointName);
        parent::tearDown();
    }
}
