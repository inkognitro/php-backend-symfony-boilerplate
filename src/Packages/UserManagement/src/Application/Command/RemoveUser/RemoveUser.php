<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\RemoveUser;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Application\Resources\User\UserId;

final class RemoveUser implements Command
{
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}