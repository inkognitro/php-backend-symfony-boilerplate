<?php declare(strict_types=1);

namespace App\Packages\Common\Application\Authorization;

final class User
{
    public const GUEST_USER_ROLE = 'guest';
    public const SYSTEM_USER_ROLE = 'system';
    private $userId;
    private $role;

    public function __construct(?string $userId, string $role)
    {
        $this->userId = $userId;
        $this->role = $role;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}