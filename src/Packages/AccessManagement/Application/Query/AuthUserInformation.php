<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

use App\Packages\UserManagement\Application\Query\User\User;

final class AuthUserInformation
{
    private $user;
    private $authUser;

    public function __construct(User $user, AuthUser $authUser)
    {
        $this->user = $user;
        $this->authUser = $authUser;
    }

    public function getAuthUser(): AuthUser
    {
        return $this->authUser;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}