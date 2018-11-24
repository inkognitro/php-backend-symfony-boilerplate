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

    public function createFromUserId(string $userId): ?User
    {
        $user = $this->userByIdQuery->execute($userId);
        if($user === null) {
            return null;
        }
        return new User($user['id'], $user['role'], $user['languageId']);
    }
}