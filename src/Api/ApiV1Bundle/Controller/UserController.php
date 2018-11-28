<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Controller;

use App\Resources\User\Application\Command\CreateUser\CreateUser;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use App\Packages\Common\Application\Authorization\User as AuthUser;

final class UserController extends Controller
{
    public function create(): Response
    {
        $request = $this->getRequest();
        $command = new CreateUser(Uuid::uuid4()->toString(), [
            'username' => 'test',
            'emailAddress' => 'test@example.com'
        ]);
        $response = $this->commandBus->handle(
            $command,
            new AuthUser('userId', AuthUser::ADMIN_USER_ROLE, 'en')
        );
        return $this->responseFactory->createFromHandlerResponse($request, $response);
    }
}
