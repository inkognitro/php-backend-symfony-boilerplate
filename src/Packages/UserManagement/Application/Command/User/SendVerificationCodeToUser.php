<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\Handlers\SendVerificationCodeToUser\SendVerificationCodeToUserHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class SendVerificationCodeToUser implements Command
{
    private $userId;
    private $emailAddress;
    private $verificationCode;
    private $executor;

    private function __construct(string $userId, string $emailAddress, ?string $verificationCode, AuthUser $executor)
    {
        $this->userId = $userId;
        $this->emailAddress = $emailAddress;
        $this->verificationCode = $verificationCode;
        $this->executor = $executor;
    }

    public static function getHandlerClass(): string
    {
        return SendVerificationCodeToUserHandler::class;
    }

    public static function create(string $userId, string $emailAddress, AuthUser $sender): self
    {
        return new self($userId, $emailAddress, null, $sender);
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