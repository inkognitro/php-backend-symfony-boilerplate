<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Handlers;

use App\Packages\JobQueueManagement\Application\Command\CreateJob;
use App\Packages\Common\Domain\CommandHandler;
use App\Packages\Common\Domain\DidNotReceiveSuccessResponseException;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Command\User\SendVerificationCodeToUser;
use App\Packages\UserManagement\Domain\UserEventDispatcher;
use App\Packages\Resources\Application\User\Write\UserParamsToSaveValidator;
use App\Packages\UserManagement\Application\Query\User\Attributes\EmailAddress;
use App\Packages\UserManagement\Application\Query\User\Attributes\Password;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;
use App\Packages\Common\Application\Query\Application\Role\RoleId;
use App\Packages\Common\Utilities\HandlerResponse\Response;
use App\Packages\Common\Utilities\HandlerResponse\Success;
use App\Packages\Common\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Domain\UserAggregate;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory;

final class CreateUserHandler
{
    private $validator;
    private $userEventDispatcher;
    private $authUserFactory;
    private $commandHandler;

    public function __construct(
        UserParamsToSaveValidator $validator,
        UserEventDispatcher $userEventDispatcher,
        AuthUserFactory $authUserFactory,
        CommandHandler $commandHandler
    )
    {
        $this->validator = $validator;
        $this->userEventDispatcher = $userEventDispatcher;
        $this->authUserFactory = $authUserFactory;
        $this->commandHandler = $commandHandler;
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