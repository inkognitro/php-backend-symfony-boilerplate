<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\SendVerificationCodeToUser;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\HandlerResponse\ResourceNotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\UserId;
use App\Packages\UserManagement\Application\Resources\User\UserRepository;
use App\Packages\UserManagement\Application\Resources\User\VerificationCode;
use App\Packages\UserManagement\Domain\User\UserAggregate;

final class SendVerificationCodeToUserHandler
{
    private $eventDispatcher;
    private $userRepository;

    public function __construct(EventDispatcher $eventDispatcher, UserRepository $userRepository)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
    }

    public function handle(SendVerificationCodeToUser $command, AuthUser $sender): Response
    {
        $user = $this->userRepository->findById(UserId::fromString($command->getUserId()));
        if ($user !== null) {
            return new ResourceNotFoundResponse();
        }

        $userAggregate = UserAggregate::fromExistingUser($user);
        $userAggregate->sendVerificationCode(VerificationCode::fromString($command->getVerificationCode()), $sender);
        $this->eventDispatcher->dispatch($userAggregate->getRecordedEvents());

        $this->sendEmail($user, VerificationCode::fromString($command->getVerificationCode()));

        $warnings = new MessageBag();
        return new SuccessResponse($warnings);
    }

    private function sendEmail(User $user, VerificationCode $verificationCode): void
    {
        //todo: send email with verification code
    }
}