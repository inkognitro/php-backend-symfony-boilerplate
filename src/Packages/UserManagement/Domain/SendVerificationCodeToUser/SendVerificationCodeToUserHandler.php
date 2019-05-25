<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\SendVerificationCodeToUser;

use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Application\HandlerResponse\ResourceNotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\SuccessResponse;
use App\Packages\Common\Application\Mailer;
use App\Packages\Common\Application\Validation\Messages\MessageBag;
use App\Packages\Common\Domain\EventDispatcher;
use App\Packages\UserManagement\Domain\User\User;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;
use App\Packages\UserManagement\Domain\User\UserAggregate;
use App\Packages\UserManagement\Domain\User\UserRepository;

final class SendVerificationCodeToUserHandler
{
    private $eventDispatcher;
    private $userRepository;
    private $mailer;

    public function __construct(EventDispatcher $eventDispatcher, UserRepository $userRepository, Mailer $mailer)
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

        $this->sendEmail($user, VerificationCode::fromString($command->getVerificationCode())); //todo: queue that!

        $userAggregate = UserAggregate::fromExistingUser($user);
        $userAggregate->sendVerificationCode(VerificationCode::fromString($command->getVerificationCode()), $sender);
        $this->userRepository->save($userAggregate);

        $warnings = new MessageBag();
        return new SuccessResponse($warnings);
    }

    private function sendEmail(User $user, VerificationCode $verificationCode): void
    {
        $subject = 'Verification';
        $link = getenv('APP_WEBFRONTEND_VERIFICATION_URL');
        $link = str_replace('%verificationCode%', urlencode($verificationCode->toString()), $link);
        $content = str_replace('%link%', $link, file_get_contents('./template.html'));
        $this->mailer->send($user->getEmailAddress()->toString(), $subject, $content);
    }
}