<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Installation\Fixtures\Dev;

use App\Packages\Common\Domain\DidNotReceiveSuccessResponseException;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\Common\Application\Command\CommandBus;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Utilities\HandlerResponse\Success;
use App\Packages\Common\Installation\Fixtures\Fixture;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory;

final class DevUserFixture extends Fixture
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
            $command = CreateUser::fromArray([
                CreateUser::USER_ID => $row['id'],
                CreateUser::USERNAME => $row['username'],
                CreateUser::EMAIL_ADDRESS => $row['username'] . '@test.com',
                CreateUser::PASSWORD => '1234',
                CreateUser::ROLE_ID => RoleId::user()->toString(),
                CreateUser::SEND_INVITATION => false,
                CreateUser::CREATOR => $authUser,
            ]);
            $response = $this->commandBus->handle($command);
            if(!$response instanceof Success) {
                throw new DidNotReceiveSuccessResponseException(
                    'Error response from fixture.'
                    . "\n" . ' Data:' . "\n" . print_r($row, true)
                    . "\n" . ' Response:' . "\n" . print_r($response, true)
                );
            }
        }
    }

    private function getRows(): array
    {
        return [
            [
                'id' => 'b8809427-8dc5-4ff8-88e1-018bcac5ef0f',
                'username' => 'lexi',
            ],
            [
                'id' => '550ec9bb-e733-44e7-afea-f63f3c6a8f1d',
                'username' => 'carla93',
            ],
            [
                'id' => '55903a4d-a02d-4c1f-9a7e-40c2d20401c7',
                'username' => 'monika',
            ],
            [
                'id' => '77843bfa-3cf9-4e9a-9d9b-ad8371201ef4',
                'username' => 'herbert77',
            ],
            [
                'id' => '0d7dfeeb-afdc-4a31-a17e-6dacf4f4d2c2',
                'username' => 'koebile1n',
            ],
            [
                'id' => '14e1350f-e576-4923-9c22-80ff9a80b5ba',
                'username' => 'fraenzi',
                'emailAddress' => 'fraenzi@dev-fixture.com',
            ],
            [
                'id' => '90bb58da-0a90-43bb-8152-50c4ed53fb33',
                'username' => 'alexa',
            ],
            [
                'id' => '1c5b035f-2a91-4b47-a2a2-3b899b356f60',
                'username' => 'aeaeaaeioukkh',
                'emailAddress' => 'aeaeaeoeueueueue@dev-fixture.com',
            ],
        ];
    }
}