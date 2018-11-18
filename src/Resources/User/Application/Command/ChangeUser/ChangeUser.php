<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\ChangeUser;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

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

    public function getPermission(): ?Permission
    {
        return null;
    }
}