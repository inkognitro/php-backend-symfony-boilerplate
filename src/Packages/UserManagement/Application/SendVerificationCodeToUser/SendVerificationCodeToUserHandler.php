<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\SendVerificationCodeToUser;

use App\Packages\UserManagement\Domain\UserRepository;
use App\Utilities\AuthUser as AuthUser;
use App\Packages\Common\Application\HandlerResponse\ResourceNotFoundResponse;
use App\Packages\Common\Application\HandlerResponse\Response;
use App\Packages\Common\Application\HandlerResponse\SuccessResponse;
use App\Packages\Common\Domain\Mailing\Mailer;
use App\Packages\Common\Domain\AuditLog\EventDispatcher;
use App\Packages\UserManagement\Domain\UserAggregate;
use App\Utilities\Validation\Messages\MessageBag;

final class SendVerificationCodeToUserHandler
{
    private $userRepository;
    private $mailer;

    public function __construct(
        UserRepository $userRepository,
        Mailer $mailer //todo remove from here and use queue!
    )
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function handle(SendVerificationCodeToUser $command, AuthUser $sender): Response
    {
        return new SuccessResponse(new MessageBag());

        /*
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
        */
    }

    private function queueSendMail(SendVerificationCodeToUser $command): void
    {
        $sendVerificationCodeCommand = SendVerificationCodeToUser::fromUserId(
            $command->getUserId(), $this->authUserFactory->createSystemUser()
        );
        $response = $this->createJobHandler->handle(CreateJob::create($sendVerificationCodeCommand));
        if (!$response instanceof Success) {
            throw new DidNotReceiveSuccessResponseException(
                'Could not create job: ' . print_r($response, true)
            );
        }
    }

    private function sendMailJob(): void //todo remove!
    {
        $sendVerificationCodeCommand = SendVerificationCodeToUser::fromUserId(
            $command->getUserId(), $this->authUserFactory->createSystemUser()
        );
        $response = $this->createJobHandler->handle(CreateJob::create($sendVerificationCodeCommand));
        if (!$response instanceof Success) {
            throw new DidNotReceiveSuccessResponseException(
                'Could not create job: ' . print_r($response, true)
            );
        }


        $subject = 'Verification';
        $link = getenv('APP_WEBFRONTEND_VERIFICATION_URL');
        $link = str_replace('%verificationCode%', urlencode($verificationCode->toString()), $link);
        $content = str_replace('%link%', $link, file_get_contents('./template.html'));
        $this->mailer->send($user->getEmailAddress()->toString(), $subject, $content);
    }

}