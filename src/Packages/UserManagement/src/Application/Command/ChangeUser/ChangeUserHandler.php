<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\ChangeUser;

use App\Packages\Common\Application\Authorization\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\NotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceChangedResponse;
use App\Packages\UserManagement\Domain\User\UserValidator;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Domain\User\Policy\ChangeUserPolicy;
use App\Packages\UserManagement\Domain\User\UserAggregate;
use App\Packages\UserManagement\Domain\User\UserRepository;

final class ChangeUserHandler
{
    private $validator;
    private $userRepository;
    private $changeUserPolicy;

    public function __construct(
        UserValidator $validator,
        UserRepository $userRepository,
        ChangeUserPolicy $changeUserPolicy
    )
    {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->changeUserPolicy = $changeUserPolicy;
    }

    public function handle(ChangeUser $command, AuthUser $authUser): Response
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

        $userToSave = UserAggregate::fromUser($user);
        $userToSave->change($userData, $authUser);
        $this->userRepository->save($userToSave);

        return new ResourceChangedResponse($userToSave->toUser(), $this->validator->getWarnings());
    }
}