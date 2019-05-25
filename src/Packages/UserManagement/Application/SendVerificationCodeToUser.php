<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Commands\SendVerificationCodeToUser;

use App\Packages\Common\Application\Command\Command;
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

    public static function getHandlerClass(): string
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