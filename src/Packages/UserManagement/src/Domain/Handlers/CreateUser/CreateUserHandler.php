<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\CommandHandling\CreateUser;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\UnauthorizedResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\JobQueuing\Application\Command\CreateJob\CreateJob;
use App\Packages\JobQueuing\Application\Command\CreateJob\CreateJobHandler;
use App\Packages\UserManagement\Application\Command\SendVerificationCodeToUser\SendVerificationCodeToUser;
use App\Packages\UserManagement\Application\Commands\CreateUser\CreateUser;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Password;
use App\Packages\UserManagement\Domain\User\User;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;
use App\Packages\UserManagement\Domain\User\UserQuery;
use App\Packages\UserManagement\Domain\User\UserRepository;
use App\Packages\UserManagement\Domain\User\UserValidator;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\UserAggregate;

final class CreateUserHandler
{
    private $validator;
    private $userQuery;
    private $userRepository;
    private $createJobHandler;

    public function __construct(
        UserValidator $validator,
        UserQuery $userQuery,
        UserRepository $userRepository,
        CreateJobHandler $createJobHandler
    ) {
        $this->validator = $validator;
        $this->userQuery = $userQuery;
        $this->userRepository = $userRepository;
        $this->createJobHandler = $createJobHandler;
    }

    public function handle(CreateUser $command, AuthUser $creator): Response
    {
        $user = $this->userQuery->findById(UserId::fromString($command->getUserId()));
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
            RoleId::fromString($role),
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            $createdAt,
            $updatedAt
        );
    }

    private function queueSendVerificationCode(User $user): void
    {
        $command = SendVerificationCodeToUser::fromUserId($user->getId()->toString());
        $this->createJobHandler->handle(CreateJob::create($command));
    }
}