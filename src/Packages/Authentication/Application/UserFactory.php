<?php declare(strict_types=1);

namespace App\Packages\Authentication\Application;

use App\Packages\Common\Application\Authorization\User;

final class UserFactory
{
    private $userByIdQuery;

    public function __construct(UserByIdQuery $userByIdQuery)
    {
        $this->userByIdQuery = $userByIdQuery;
    }

    public function createGuestUser(): User
    {
        return new User(null, User::GUEST_USER_ROLE);
    }

    public function createSystemUser(): User
    {
        return new User(null, User::SYSTEM_USER_ROLE);
    }

    public function createFromUserId(string $userId): User
    {
        $user = $this->userByIdQuery->execute($userId);
        if($user === null) {
            return $this->createGuestUser();
        }
        return new User($user['id'], $user['role']);
    }

    public function createFromJsonWebToken(string $jsonWebToken): User
    {
        //todo: validate JWT and use createFromUserId
        return $this->createGuestUser();
    }
}