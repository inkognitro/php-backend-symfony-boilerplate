<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\ChangeUser;

use App\Packages\Common\Application\Command\Command;

final class ChangeUser implements Command
{
    private $userId;
    private $userData;

    public function __construct(string $userId, array $userData)
    {
        $this->userId = $userId;
        $this->userData = $userData;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getUserData(): array
    {
        return $this->userData;
    }
}