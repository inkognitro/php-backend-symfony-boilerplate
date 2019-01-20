<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Mailing;

interface Mailer
{
    public function send(string $receiverEmailAddress, string $subject, string $content): void;
}