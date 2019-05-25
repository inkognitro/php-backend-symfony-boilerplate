<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\CreateUser;

use App\Packages\UserManagement\Domain\UserValidation\UserValidator;
use App\Resources\User\UserId;
use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Application\CreateUser;
use App\Packages\UserManagement\Domain\User\User;
use App\Packages\UserManagement\Domain\User\UserAggregate;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;

    public function __construct(
        UserValidator $validator,
        UserRepository $userRepository
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
    }

    public function handle(CreateUser $command, AuthUser $creator): Response
    {
        $user = $this->userQueryHandler->findById(UserId::fromString($command->getUserId()));
        if ($user !== null) {
            return new UnauthorizedResponse();
        }

        $user = $this->createUserFromCommand($command);

        $this->validator->validate($user);
        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }

        $userAggregate = UserAggregate::fromNewUser($user, $creator);
        $this->userRepository->save($userAggregate);

        if ($command->sendInvitation()) {
            $this->queueSendVerificationCode($user);
        }

        return new ResourceCreatedResponse($this->validator->getWarnings());
    }

    private function createUserFromCommand(CreateUser $command): User
    {
        $role = ($command->getRole() !== null ? $command->getRole() : AuthUser::DEFAULT_USER_ROLE);
        $verificationCode = null;
        $verificationCodeSentAt = null;
        $verifiedAt = null;
        $createdAt = null;
        $updatedAt = null;
        return new User(
            UserId::fromString($command->getUserId()),
            Username::fromString($command->getUsername()),
            EmailAddress::fromString($command->getEmailAddress()),
            Password::fromString($command->getEmailAddress()),
            RoleId::fromString($role),
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            $createdAt,
            $updatedAt
        );
    }

    /*
    private function queueSendVerificationCode(User $user): void //todo
    {
        $command = SendVerificationCodeToUser::fromUserId($user->getId()->toString());
        $this->createJobHandler->handle(CreateJob::create($command));
    }
    */
}