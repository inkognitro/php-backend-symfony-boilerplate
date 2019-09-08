<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

final class AuthUserInformationByUserIdQuery
{
    private $userId;

    private function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public static function fromUserId(string $userId): self
    {
        return new self($userId);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}