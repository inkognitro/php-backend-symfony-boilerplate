<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\SendVerificationCodeToUser;

use App\Packages\Common\Application\Command\Command;
use App\Packages\JobQueuing\Application\Command\QueueableCommand;
use Ramsey\Uuid\Uuid;

final class SendVerificationCodeToUser implements Command, QueueableCommand
{
    private $userId;
    private $verificationCode;

    private function __construct(string $userId, string $verificationCode)
    {
        $this->userId = $userId;
        $this->verificationCode = $verificationCode;
    }

    public function jsonSerialize(): array
    {
        return [
            'userId' => $this->userId,
            'verificationCode' => $this->verificationCode
        ];
    }

    public static function create(string $userId): self
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