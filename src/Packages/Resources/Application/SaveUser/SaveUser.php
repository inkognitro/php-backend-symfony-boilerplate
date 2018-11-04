<?php declare(strict_types=1);

namespace App\Packages\Resources\Application\SaveUser;

use App\Packages\Common\Application\Authorization\Permission;
use App\Packages\Common\Application\CommandHandling\Command;

final class SaveUser implements Command
{
    private $userData;

    public function __construct(array $userData)
    {
        $this->userData = $userData;
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