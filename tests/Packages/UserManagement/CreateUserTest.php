<?php declare(strict_types=1);

namespace App\Tests\Packages\UserManagement;

use App\Packages\UserManagement\Application\Commands\CreateUser\CreateUser;
use App\Tests\Packages\PackageTestCase;
use Ramsey\Uuid\Uuid;

final class CreateUserTest extends PackageTestCase
{
    /** @var UserProjectionGateway */
    private $userProjectionGateway;

    public function setUp()
    {
        parent::setUp();
        $this->userProjectionGateway = new UserProjectionGateway($this->getConnection());
    }

    public function testMustCreateUser(): void
    {
        $userId = Uuid::uuid4()->toString();
        $command = CreateUser::fromArray([
            CreateUser::ID => $userId,
            CreateUser::PASSWORD => '1234',
            CreateUser::EMAIL_ADDRESS => 'foo@bar.com',
            CreateUser::USERNAME => 'foo',
            CreateUser::SEND_INVITATION => false,
        ]);
        $this->getCommandBus()->handle($command, $this->createSystemAuthUser());
        $row = $this->userProjectionGateway->getRowById($userId);
        self::assertNotNull($row);
        //todo: assert fields
    }
}
