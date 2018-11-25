<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Resources\Common\Application\HandlerResponse\CreationSuccessResponse;
use App\Resources\User\Application\Command\UserDataValidator;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Domain\User;
use App\Resources\User\Application\Domain\UserRepository;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;

    public function __construct(UserDataValidator $validator, UserRepository $userRepository)
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUser $command, AuthUser $authUser): HandlerResponse
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

        $userToSave = User::create($userData, $authUser);
        $this->userRepository->save($userToSave);

        return new CreationSuccessResponse($userToSave->toQueryUser(), $this->validator->getWarnings());
    }
}