<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Infrastructure\Query\AuthUser;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserByCredentialsQuery;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserByCredentialsQueryHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser\Attributes\LanguageId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\AccessManagement\Application\Query\AuthUser\Attributes\RoleId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Password;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\Common\Application\Query\Like;

final class DbalAuthUserByCredentialsQueryHandler implements AuthUserByCredentialsQueryHandler
{
    private $usersQueryHandler;

    public function __construct(UsersQueryHandler $usersQueryHandler)
    {
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function handle(AuthUserByCredentialsQuery $usersByCredentialsQuery): ?AuthUser
    {
        $query = UsersQuery::createFromVerifiedUsers([
            UserId::class,
            Password::class,
            RoleId::class,
        ]);
        $query = $query->andWhere(new Like(Username::class, $usersByCredentialsQuery->getUsername()));
        $user = $this->usersQueryHandler->handle($query)->findFirst();

        $password = Password::fromString($usersByCredentialsQuery->getPassword());
        if($user === null || !$user->getPassword()->isSame($password)) {
            return null;
        }

        $languageId = LanguageId::fromString('en'); //todo: integrate language by api header!
        return new AuthUser($user->getId(), $user->getRoleId(), $languageId);
    }
}