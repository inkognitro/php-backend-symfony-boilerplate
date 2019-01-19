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
use Swift_Mailer;
use Swift_Message;

final class SendVerificationCodeToUserHandler
{
    private $eventDispatcher;
    private $userRepository;
    private $mailer;

    public function __construct(EventDispatcher $eventDispatcher, UserRepository $userRepository, Swift_Mailer $mailer)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
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
        $link = str_replace('%verificationCode%', $verificationCode->toString(), getenv('APP_FRONTEND_VERIFICATION_URL'));
        $body = str_replace('%link%', $link, file_get_contents('./template.html'));
        $bodyContentType = 'text/html';
        $message = new Swift_Message('Account Verification');
        $message->setFrom(getenv('APP_EMAIL'));
        $message->setTo($user->getEmailAddress()->toString());
        $message->setBody($body, $bodyContentType);
        $this->mailer->send($message);
    }
}