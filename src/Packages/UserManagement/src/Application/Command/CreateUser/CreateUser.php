<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\CreateUser;

use App\Packages\Common\Application\Command\Command;
use App\Packages\UserManagement\Application\Resources\User\User;

final class CreateUser implements Command
{
    private $user;
    private $sendInvitation;

    public function __construct(User $user, bool $sendInvitation)
    {
        $this->user = $user;
        $this->sendInvitation = $sendInvitation;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function sendInvitation(): bool
    {
        return $this->sendInvitation;
    }
}