<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\SendVerificationCodeToUser\SendVerificationCodeToUserHandler;

final class SendVerificationCodeToUser implements Command
{
    private $userId;
    private $verificationCode;

    private function __construct(string $userId, ?string $verificationCode)
    {
        $this->userId = $userId;
        $this->verificationCode = $verificationCode;
    }

    public static function getHandlerClass(): string
    {
        return SendVerificationCodeToUserHandler::class;
    }

    public static function fromUserId(string $userId): self
    {
        return new self($userId, null);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }
}