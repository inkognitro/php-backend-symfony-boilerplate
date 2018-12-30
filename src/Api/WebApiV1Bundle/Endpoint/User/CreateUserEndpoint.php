<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle\Endpoint\User;

use App\Api\WebApiV1Bundle\Endpoint\Endpoint;
use App\Api\WebApiV1Bundle\Endpoint\EndpointSchema;
use App\Api\WebApiV1Bundle\Response\Response;
use App\Api\WebApiV1Bundle\Response\ResponseFactory;
use App\Packages\Common\Application\CommandBus;
use App\Resources\User\Application\Command\CreateUser\CreateUser;
use Ramsey\Uuid\Uuid;

final class CreateUserEndpoint implements Endpoint
{
    private $commandBus;
    private $responseFactory;

    public function __construct(CommandBus $commandBus, ResponseFactory $responseFactory)
    {
        $this->commandBus = $commandBus;
        $this->responseFactory = $responseFactory;
    }

    public function handle(): Response
    {
        die('CreateUserEndpoint ok!'); //todo: remove!
        $command = new CreateUser(Uuid::uuid4()->toString(), [
            'username' => 'test',
            'emailAddress' => 'test@example.com'
        ]);
        $handlerResponse = $this->commandBus->handle($command, $authUser);
        return $this->responseFactory->createFromHandlerResponse($handlerResponse);
    }

    public function getSchema(): EndpointSchema
    {
        return EndpointSchema::fromJsonFile(__DIR__ . '/CreateUserEndpointSchema.json');
    }
}
