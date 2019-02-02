<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\JobQueuing\Application\Command\CreateJob\CreateJob;
use App\Packages\JobQueuing\Application\Command\CreateJob\CreateJobHandler;
use App\Packages\UserManagement\Application\Command\SendVerificationCodeToUser\SendVerificationCodeToUser;
use App\Packages\UserManagement\Application\Resources\User\EmailAddress;
use App\Packages\UserManagement\Application\Resources\User\Password;
use App\Packages\UserManagement\Application\Resources\User\Role;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\Username;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use App\Packages\UserManagement\Domain\User\UserValidator;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Domain\User\UserAggregate;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;
    private $eventDispatcher;
    private $createJobHandler;

    public function __construct(
        UserValidator $validator,
        UserRepository $userRepository,
        EventDispatcher $eventDispatcher,
        CreateJobHandler $createJobHandler
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->createJobHandler = $createJobHandler;
    }

    public function handle(CreateUser $command, AuthUser $creator): Response
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
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
        $this->eventDispatcher->dispatch($userAggregate->getRecordedEvents());

        if($command->sendInvitation()) {
            $this->queueSendVerificationCode($user);
        }

        return new ResourceCreatedResponse($user, $this->validator->getWarnings());
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
            Role::fromString($role),
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            $createdAt,
            $updatedAt
        );
    }

    private function queueSendVerificationCode(User $user): void
    {
        $command = SendVerificationCodeToUser::create($user->getId()->toString());
        $this->createJobHandler->handle(CreateJob::create($command));
    }
}