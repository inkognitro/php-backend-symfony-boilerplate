<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Handlers;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\JobQueueManagement\Application\Command\CreateJob;
use App\Packages\Common\Domain\CommandHandler;
use App\Packages\Common\Domain\DidNotReceiveSuccessResponseException;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\SendVerificationCodeToUser;
use App\Packages\UserManagement\Application\Command\User\UserParamsValidator;
use App\Packages\UserManagement\Domain\UserEventDispatcher;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\Common\Utilities\HandlerResponse\Response;
use App\Packages\Common\Utilities\HandlerResponse\Success;
use App\Packages\Common\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Domain\UserAggregate;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory;

final class CreateUserHandler
{
    private $userParamsValidator;
    private $userEventDispatcher;
    private $authUserFactory;
    private $commandHandler;

    public function __construct(
        UserParamsValidator $userParamsValidator,
        UserEventDispatcher $userEventDispatcher,
        AuthUserFactory $authUserFactory,
        CommandHandler $commandHandler
    )
    {
        $this->userParamsValidator = $userParamsValidator;
        $this->userEventDispatcher = $userEventDispatcher;
        $this->authUserFactory = $authUserFactory;
        $this->commandHandler = $commandHandler;
    }

    public function handle(CreateUser $command): Response
    {
        $userParams = $command->getUserParams();
        $validationResult = $this->userParamsValidator->validateCreation($userParams);
        if (!$validationResult->isValid()) {
            return ValidationErrorResponse::fromValidationResult($validationResult);
        }

        UserAggregate::fromUserParams($userParams);

        $userAggregate = UserAggregate::create(
            UserId::fromString($userParams->getUserId()),
            Username::fromString($userParams->getUsername()),
            EmailAddress::fromString($userParams->getEmailAddress()),
            Password::fromString($userParams->getPassword()),
            RoleId::fromString($userParams->getRoleId()),
            $command->getCommandExecutor()
        );

        $this->userEventDispatcher->dispatchEventsFromUserAggregate($userAggregate);
        if ($command->sendInvitation()) {
            $this->queueSendVerificationCode($command);
        }
        return new ResourceCreatedResponse($this->validator->getWarnings());
    }

    private function queueSendVerificationCode(CreateUser $command): void
    {
        $systemAuthUser = $this->authUserFactory->createSystemUser();
        $commandToQueue = SendVerificationCodeToUser::create($command->getUserId(), $command->getEmailAddress(), $systemAuthUser);
        $createJobCommand = CreateJob::create($commandToQueue, $systemAuthUser);
        $response = $this->commandHandler->handle($createJobCommand);
        if (!$response instanceof Success) {
            throw new DidNotReceiveSuccessResponseException(
                'Could not create job: ' . print_r($response, true)
            );
        }
    }
}