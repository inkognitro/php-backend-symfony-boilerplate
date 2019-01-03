<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Migrations;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Authorization\UserFactory as AuthUserFactory;
use App\Packages\Common\Application\CommandBus;
use App\Packages\Common\Installation\Fixtures\Fixture;
use App\Packages\UserManagement\Application\Command\CreateUser\CreateUser;

final class UserFixture implements Fixture
{
    private $commandBus;
    private $authUserFactory;

    public function __construct(CommandBus $commandBus, AuthUserFactory $authUserFactory)
    {
        $this->commandBus = $commandBus;
        $this->authUserFactory = $authUserFactory;
    }

    public function execute(): void
    {
        $authUser = $this->authUserFactory->createSystemUser();
        foreach($this->getRows() as $row) {
            $data = array_merge(['sendInvitation' => false], $row);
            $this->commandBus->handle(CreateUser::fromArray($data), $authUser);
        }
    }

    private function getRows(): array
    {
        return [
            [
                'id' => '287d6446-af61-4451-bc60-85ea545e53b6',
                'username' => 'admin',
                'emailAddress' => 'admin@example.com',
                'password' => 'test',
                'role' => AuthUser::ADMIN_USER_ROLE,
            ],
            [
                'id' => '4044de59-40fc-4f87-bf8c-df5554a35240',
                'username' => 'user',
                'emailAddress' => 'user@example.com',
                'password' => 'test',
                'role' => AuthUser::DEFAULT_USER_ROLE,
            ],
        ];
    }
}