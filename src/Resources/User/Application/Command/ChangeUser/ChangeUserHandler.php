<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\ChangeUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\HandlerResponse;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\NotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Resources\Common\Application\HandlerResponse\ChangeSuccessResponse;
use App\Resources\User\Application\Command\UserDataValidator;
use App\Resources\User\Application\Attribute\UserId;
use App\Resources\User\Application\Domain\Policy\ChangeUserPolicy;
use App\Resources\User\Application\Domain\User;
use App\Resources\User\Application\Domain\UserRepository;

final class ChangeUserHandler
{
    private $validator;
    private $userRepository;
    private $changeUserPolicy;

    public function __construct(
        UserDataValidator $validator,
        UserRepository $userRepository,
        ChangeUserPolicy $changeUserPolicy
    )
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->changeUserPolicy = $changeUserPolicy;
    }

    public function handle(ChangeUser $command, AuthUser $authUser): HandlerResponse
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user === null) {
            return new NotFoundResponse();
        }

        if (!$this->changeUserPolicy->isAuthorized($authUser, $user)) {
            return new UnauthorizedResponse();
        }

        $userData = array_merge($user->toArray(), $command->getUserData(), ['id' => $user->getId()->toString()]);
        $this->validator->validate($userData);

        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $userToSave = User::fromUser($user);
        $userToSave->change($userData, $authUser);
        $this->userRepository->save($userToSave);

        return new ChangeSuccessResponse($userToSave->toQueryUser(), $this->validator->getWarnings());
    }
}