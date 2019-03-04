<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\SendVerificationCodeToUser;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\Handlers\SendVerificationCodeToUser\SendVerificationCodeToUserHandler;
use Ramsey\Uuid\Uuid;

final class SendVerificationCodeToUser implements Command
{
    private $userId;
    private $verificationCode;

    private function __construct(string $userId, string $verificationCode)
    {
        $this->userId = $userId;
        $this->verificationCode = $verificationCode;
    }

    public static function getHandlerClassName(): string
    {
        return SendVerificationCodeToUserHandler::class;
    }

    public static function fromUserId(string $userId): self
    {
        $verificationCode = str_replace('-', '', Uuid::uuid4()->toString());
        return new self($userId, $verificationCode);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }
}