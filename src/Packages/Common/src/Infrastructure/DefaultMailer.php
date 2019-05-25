<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure;

use App\Packages\Common\Application\Mailer;
use Swift_Mailer;
use Swift_Message;

final class DefaultMailer implements Mailer
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send(string $receiverEmailAddress, string $subject, string $content): void
    {
        $message = new Swift_Message($subject);
        $message->setFrom(getenv('APP_EMAIL_ADDRESS_FROM'));
        $message->setTo($receiverEmailAddress);
        $contentType = 'text/html';
        $message->setBody($content, $contentType);
        $x = $this->mailer->send($message);
        echo 'sent emails = '. $x;
    }
}