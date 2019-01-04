<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Fixtures\Prod;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\Authorization\UserFactory as AuthUserFactory;
use App\Packages\Common\Application\CommandBus;
use App\Packages\Common\Installation\Fixtures\AbstractFixture;
use App\Packages\UserManagement\Application\Command\CreateUser\CreateUser;

final class ProdUserFixture extends AbstractFixture
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
                'id' => '11111111-1111-1111-1111-111111111111',
                'username' => getenv('APP_ADMIN_USERNAME'),
                'emailAddress' => getenv('APP_ADMIN_EMAIL'),
                'password' => getenv('APP_ADMIN_PASSWORD'),
                'role' => AuthUser::ADMIN_USER_ROLE,
            ]
        ];
    }
}