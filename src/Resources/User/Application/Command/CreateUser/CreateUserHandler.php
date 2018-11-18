<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\CommandHandling\HandlerResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\CommandHandling\HandlerResponse\UnauthorizedResponse;
use App\Resources\User\Application\Command\CommandUser;
use App\Resources\User\Application\Command\UserCommandPolicy;
use App\Resources\User\Application\Command\UserDataValidator;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\UserRepository;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;
    private $userCommandPolicy;

    public function __construct(
        UserDataValidator $validator,
        UserRepository $userRepository,
        UserCommandPolicy $userCommandPolicy
    )
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->userCommandPolicy = $userCommandPolicy;
    }

    public function handle(CreateUser $command, AuthUser $authUser): HandlerResponse
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user !== null) {
            return new UnauthorizedResponse();
        }

        if (!$this->userCommandPolicy->create($authUser)) {
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

        $userToSave = CommandUser::create($userData, $authUser);
        $this->userRepository->save($userToSave);

        return new SuccessResponse($userToSave->toUser()->toArray(), $this->validator->getWarnings());
    }
}