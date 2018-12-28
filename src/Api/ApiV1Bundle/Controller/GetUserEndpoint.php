<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Controller;

use App\Resources\User\Application\Command\CreateUser\CreateUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

final class GetUserEndpoint extends Controller
{
    public function handle(): Response
    {
        die('test ok');

        $request = $this->createRequest();
        $command = new CreateUser(Uuid::uuid4()->toString(), [
            'username' => 'test',
            'emailAddress' => 'test@example.com'
        ]);
        $authUser = $this->authUserFactory->createFromUserId('287d6446-af61-4451-bc60-85ea545e53b6');
        $response = $this->commandBus->handle($command, $authUser);
        return $this->httpResponseFactory->createFromHandlerResponse($response, $request);
    }
}
