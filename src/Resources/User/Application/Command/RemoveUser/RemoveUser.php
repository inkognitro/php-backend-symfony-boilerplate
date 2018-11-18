<?php declare(strict_types=1);

namespace App\Resources\User\Application\Command\RemoveUser;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class RemoveUser implements Command
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getPermission(): ?Permission
    {
        return null;
    }
}