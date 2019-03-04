<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\RemoveUser;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Domain\Handlers\RemoveUser\RemoveUserHandler;

final class RemoveUser implements Command
{
    private $userId;

    public function __construct(string $userId)
    {
        $this->userId = $userId;
    }

    public static function getHandlerClassName(): string
    {
        return RemoveUserHandler::class;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}