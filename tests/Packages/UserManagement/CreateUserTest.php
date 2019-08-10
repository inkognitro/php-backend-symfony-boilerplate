<?php declare(strict_types=1);

namespace App\Tests\Packages\UserManagement;

use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\Common\Application\Query\Like;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Tests\Packages\PackageTestCase;

final class CreateUserTest extends PackageTestCase
{
    public function testCanCreateUser(): void
    {
        $command = CreateUser::fromArray([
            CreateUser::USER_PARAMS => UserParams::fromArray([
                UserId::class => Text::fromString('f28e722e-fa50-43c4-b603-58dfc4c39622'),
                Username::class => Text::fromString('f28e722e'),
                Password::class => Text::fromString('foo1234'),
                EmailAddress::class => Text::fromString('f28e722e@bar.com'),
            ]),
            CreateUser::SEND_INVITATION => false,
            CreateUser::CREATOR => $this->createSystemAuthUser(),
        ]);
        $response = $this->getCommandBus()->handle($command);
        $this->assertSuccessResponse($response);
        $this->assertUserData([
            UserId::class => 'f28e722e-fa50-43c4-b603-58dfc4c39622',
        ]);
    }

    private function assertUserData(array $userData): void
    {
        /** @var $usersQueryHandler UsersQueryHandler */
        $usersQueryHandler = $this->getContainer()->get(UsersQueryHandler::class);
        $condition = new Like(UserId::class, $userData[UserId::class]);
        $query = UsersQuery::create([UserId::class])->andWhere($condition);
        $users = $usersQueryHandler->handle($query);
        $user = $users->findFirst();
        self::assertNotNull($user);
    }
}
