<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\Handlers\SendVerificationCodeToUser;

use App\Packages\Common\Domain\Mailing\Mailer;
use App\Packages\UserManagement\Application\SendVerificationCodeToUser;
use App\Packages\UserManagement\Domain\UserRepository;
use App\Utilities\HandlerResponse\Response;
use App\Utilities\HandlerResponse\SuccessResponse;
use App\Utilities\Validation\Messages\MessageBag;

final class SendVerificationCodeToUserHandler
{
    private $userRepository;
    private $mailer;

    public function __construct(UserRepository $userRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function handle(SendVerificationCodeToUser $command): Response
    {
        $subject = 'Verification';
        $link = getenv('APP_WEBFRONTEND_VERIFICATION_URL');
        $link = str_replace('%verificationCode%', urlencode($verificationCode->toString()), $link);
        $content = str_replace('%link%', $link, file_get_contents('./template.html'));
        //$this->mailer->send($user->getEmailAddress()->toString(), $subject, $content); //todo
        return new SuccessResponse(MessageBag::createEmpty());
    }
}