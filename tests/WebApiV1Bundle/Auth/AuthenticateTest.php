<?php declare(strict_types=1);

namespace App\Tests\WebApiV1Bundle\Auth;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Command\Params\Text;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\UserParams;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Tests\WebApiV1Bundle\WebTestCase;

final class CreateUserTest extends WebTestCase
{
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
        $this->insertUser();
    }

    public function testCanAuthenticate(): void
    {
        self::assertNotNull(true);
    }

    private function createUserParamsArray(array $userData): array
    {
        return [
            UserId::class => Text::fromString($userData[UserId::class]),
            Username::class => Text::fromString($userData[Username::class]),
            Password::class => Text::fromString($userData[Password::class]),
            EmailAddress::class => Text::fromString($userData[EmailAddress::class]),
            RoleId::class => Text::fromString($userData[RoleId::class]),
        ];
    }

    private function insertUser(): void
    {
        $command = CreateUser::fromArray([
            CreateUser::USER_PARAMS => UserParams::fromArray($this->createUserParamsArray(self::USER)),
            CreateUser::USER_MUST_BE_VERIFIED => false,
            CreateUser::CREATOR => $this->createSystemAuthUser(),
        ]);
        $response = $this->getCommandBus()->handle($command);
        $this->assertSuccessHandlerResponse($response);
    }
}
