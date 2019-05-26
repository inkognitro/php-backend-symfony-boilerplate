<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application;

use App\Packages\Common\Application\CommandBus;
use App\Packages\Common\Application\DidNotReceiveSuccessResponseException;
use App\Packages\Common\Application\HandlerResponse\Success;
use App\Packages\UserManagement\Application\SendVerificationCodeToUser\SendVerificationCodeToUser;
use App\Packages\UserManagement\Domain\UserRepository;
use App\Packages\UserManagement\Domain\UserValidation\UserValidator;
use App\Resources\User\EmailAddress;
use App\Resources\User\Password;
use App\Resources\User\UserId;
use App\Resources\User\Username;
use App\Resources\UserRole\RoleId;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Domain\UserAggregate;
use App\Utilities\AuthUserFactory;

final class CreateUserHandler
{
    private $validator;
    private $userRepository;
    private $authUserFactory;
    private $commandBus;

    public function __construct(
        UserValidator $validator,
        UserRepository $userRepository,
        AuthUserFactory $authUserFactory,
        CommandBus $commandBus
    ) {
        $this->validator = $validator;
        $this->userRepository = $userRepository;
        $this->authUserFactory = $authUserFactory;
        $this->commandBus = $commandBus;
    }

    public function handle(CreateUser $command): Response
    {
        $this->validator->validateCreation($command);
        if ($this->validator->hasErrors()) {
            return new ValidationErrorResponse(
                $this->validator->getErrors(),
                $this->validator->getWarnings()
            );
        }
        $userAggregate = UserAggregate::create(
            UserId::fromString($command->getUserId()),
            Username::fromString($command->getUsername()),
            EmailAddress::fromString($command->getEmailAddress()),
            Password::fromString($command->getPassword()),
            RoleId::fromString($command->getRoleId()),
            $command->getExecutor()
        );
        $this->userRepository->save($userAggregate);
        if ($command->sendInvitation()) {
            $this->sendVerificationCode($command);
        }
        return new ResourceCreatedResponse($this->validator->getWarnings());
    }

    private function sendVerificationCode(CreateUser $command): void
    {
        $sendVerificationCodeCommand = SendVerificationCodeToUser::fromUserId(
            $command->getUserId(), $this->authUserFactory->createSystemUser()
        );
        $response = $this->commandBus->handle($sendVerificationCodeCommand);
        if (!$response instanceof Success) {
            throw new DidNotReceiveSuccessResponseException(
                'Could not create job: ' . print_r($response, true)
            );
        }
    }
}