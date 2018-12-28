<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Endpoint\User;

use App\Api\ApiV1Bundle\Endpoint\Endpoint;
use App\Api\ApiV1Bundle\Endpoint\EndpointSchema;
use App\Api\ApiV1Bundle\Response\Response;
use App\Api\ApiV1Bundle\Response\ResponseFactory;
use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandBus;
use App\Resources\User\Application\Command\CreateUser\CreateUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

final class CreateUserEndpoint implements Endpoint
{
    private $commandBus;
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactory $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function handle(Request $request, AuthUser $authUser, array $params): Response
    {
        die('CreateUserEndpoint ok! Params: ' . print_r($params, true)); //todo: remove!
        $command = new CreateUser(Uuid::uuid4()->toString(), [
            'username' => 'test',
            'emailAddress' => 'test@example.com'
        ]);
        $handlerResponse = $this->commandBus->handle($command, $authUser);
        return $this->responseFactory->createFromHandlerResponse($handlerResponse);
    }

    public static function getSchema(): EndpointSchema
    {
        return EndpointSchema::fromJsonFile(__DIR__ . '/CreateUserSchema.json');
    }
}
