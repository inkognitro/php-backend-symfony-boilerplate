<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\SendVerificationCodeToUser;

use App\Packages\Common\Application\Command;
use App\Utilities\AuthUser;

final class SendVerificationCodeToUser implements Command
{
    private $userId;
    private $verificationCode;
    private $executor;

    private function __construct(string $userId, ?string $verificationCode, AuthUser $executor)
    {
        $this->userId = $userId;
        $this->verificationCode = $verificationCode;
        $this->executor = $executor;
    }

    public static function getHandlerClass(): string
    {
        return SendVerificationCodeToUserHandler::class;
    }

    public static function fromUserId(string $userId, AuthUser $sender): self
    {
        return new self($userId, null, $sender);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function getExecutor(): AuthUser
    {
        return $this->executor;
    }
}