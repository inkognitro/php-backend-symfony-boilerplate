<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Domain\User\UserValidator;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Domain\User\UserAggregate;
use App\Packages\UserManagement\Domain\User\UserRepository;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;

    public function __construct(UserValidator $validator, UserRepository $userRepository)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUser $command, AuthUser $authUser): Response
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user !== null) {
            return new UnauthorizedResponse();
        }

        $userData = array_merge($user->toArray(), $command->getUserData());
        $this->validator->validate($userData);

        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $userToSave = UserAggregate::create($userData, $authUser);
        $this->userRepository->save($userToSave);

        return new ResourceCreatedResponse($userToSave->toUser(), $this->validator->getWarnings());
    }
}