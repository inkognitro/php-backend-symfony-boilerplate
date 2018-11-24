<?php declare(strict_types=1);

namespace App\Resources\User\Application\Query;

use App\Packages\Common\Application\Authorization\User as AuthUser;

final class UsersQuery
{
    private $emailAddress;
    private $authUser;

    public function __construct(string $emailAddress, AuthUser $authUser)
    {
        $this->emailAddress = $emailAddress;
        $this->authUser = $authUser;
    }

    public function getAuthUser(): AuthUser
    {
        return $this->authUser;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }
}