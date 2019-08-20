<?php declare(strict_types=1);

namespace App\Tests\Packages\UserManagement;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\Common\Application\Query\Like;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Tests\Packages\PackageTestCase;

abstract class UserTestCase extends PackageTestCase
{
    protected const EXISTING_USER = [
        UserId::class => '2df55a50-b64e-4677-b04c-faef187c3f8e',
        Username::class => 'username2df55a50',
        EmailAddress::class => '2df55a50@bar.com',
        Password::class => 'foo123577',
        RoleId::class => 'user',
    ];

    protected const USER = [
        UserId::class => 'f28e722e-fa50-43c4-b603-58dfc4c39622',
        Username::class => 'f28e722e',
        EmailAddress::class => 'f28e722e@bar.com',
        Password::class => 'foo1234',
        RoleId::class => 'user',
    ];

    public function setUp()
    {
        parent::setUp();
        $this->insertExistingUser();
    }

    protected function findUser(string $userId, array $attributesToSelect): ?User
    {
        /** @var $usersQueryHandler UsersQueryHandler */
        $usersQueryHandler = $this->getContainer()->get(UsersQueryHandler::class);
        $condition = new Like(UserId::class, $userId);
        $query = UsersQuery::create($attributesToSelect)->andWhere($condition);
        $users = $usersQueryHandler->handle($query);
        return $users->findFirst();
    }

    protected function createUserParamsArray(array $userData): array
    {
        return [
            UserId::class => Text::fromString($userData[UserId::class]),
            Username::class => Text::fromString($userData[Username::class]),
            Password::class => Text::fromString($userData[Password::class]),
            EmailAddress::class => Text::fromString($userData[EmailAddress::class]),
            RoleId::class => Text::fromString($userData[RoleId::class]),
        ];
    }

    private function insertExistingUser(): void
    {
        $command = CreateUser::fromArray([
            CreateUser::USER_PARAMS => UserParams::fromArray($this->createUserParamsArray(self::EXISTING_USER)),
            CreateUser::SEND_INVITATION => false,
            CreateUser::CREATOR => $this->createSystemAuthUser(),
        ]);
        $response = $this->getCommandBus()->handle($command);
        $this->assertSuccessResponse($response);
    }
}
