<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Handlers;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Utilities\DateTimeFactory;
use App\Packages\Common\Domain\CommandHandler;
use App\Packages\UserManagement\Application\Command\User\CreateUser;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerifiedAt;
use App\Packages\UserManagement\Domain\UserParamsValidation\UserParamsValidator;
use App\Packages\UserManagement\Domain\UserEventDispatcher;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\Common\Application\Utilities\HandlerResponse\Response;
use App\Packages\Common\Application\Utilities\HandlerResponse\ValidationErrorResponse;
use App\Packages\Common\Application\Utilities\HandlerResponse\ResourceCreatedResponse;
use App\Packages\UserManagement\Domain\UserAggregate;
use App\Packages\AccessManagement\Application\Query\AuthUserFactory;

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
        $validationResult = $this->userParamsValidator->validateCreation($userParams, $command->getCommandExecutor());
        if (!$validationResult->isValid()) {
            return ValidationErrorResponse::fromValidationResult($validationResult);
        }

        $userAggregate = UserAggregate::create(
            $this->createUserFromCommand($command),
            $command->getCommandExecutor()
        );

        $this->userEventDispatcher->dispatchEventsFromUserAggregate($userAggregate);

        if ($command->userMustBeVerified()) {
            $this->queueSendVerificationCode($command);
        }

        /** @var $resource Resource */
        $resource = $userAggregate->toUser();
        return new ResourceCreatedResponse($resource);
    }

    private function createUserFromCommand(CreateUser $command): User
    {
        $userParams = $command->getUserParams();
        return User::create()->modifyByArray([
            UserId::class => UserId::fromString($userParams->getId()->toTrimmedLowerCaseString()),
            Username::class => Username::fromString($userParams->getUsername()->toTrimmedString()),
            EmailAddress::class => EmailAddress::fromString($userParams->getEmailAddress()->toTrimmedString()),
            Password::class => Password::fromString($userParams->getPassword()->toString()),
            RoleId::class => RoleId::fromString($userParams->getRoleId()->toString()),
            VerifiedAt::class => (VerifiedAt::fromNullableDateTime($command->userMustBeVerified() ? null : DateTimeFactory::create()))
        ]);
    }

    private function queueSendVerificationCode(CreateUser $command): void
    {
        //todo

        /*
        $systemAuthUser = $this->authUserFactory->createSystemUser();
        $commandToQueue = SendVerificationCodeToUser::create($command->getUserId(), $command->getEmailAddress(), $systemAuthUser);
        $createJobCommand = CreateJob::create($commandToQueue, $systemAuthUser);
        $response = $this->commandHandler->handle($createJobCommand);
        if (!$response instanceof Success) {
            throw new DidNotReceiveSuccessResponseException(
                'Could not create job: ' . print_r($response, true)
            );
        }
        */
    }
}