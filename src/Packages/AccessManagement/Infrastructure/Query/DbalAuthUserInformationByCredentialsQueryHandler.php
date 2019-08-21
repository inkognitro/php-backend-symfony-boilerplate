<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Infrastructure\Query;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\Query\AuthUserInformation;
use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQuery;
use App\Packages\AccessManagement\Application\Query\AuthUserInformationByCredentialsQueryHandler;
use App\Packages\Common\Application\Query\Equals;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;

final class DbalAuthUserInformationByCredentialsQueryHandler implements AuthUserInformationByCredentialsQueryHandler
{
    private $usersQueryHandler;

    public function __construct(UsersQueryHandler $usersQueryHandler)
    {
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function handle(AuthUserInformationByCredentialsQuery $usersByCredentialsQuery): ?AuthUserInformation
    {
        $query = UsersQuery::createFromVerifiedUsers([
            UserId::class,
            Username::class,
            EmailAddress::class,
            Password::class,
            RoleId::class,
        ]);
        $query = $query->andWhere(new Equals(Username::class, $usersByCredentialsQuery->getUsername()));
        $user = $this->usersQueryHandler->handle($query)->findFirst();
        if ($user === null || !$user->getPassword()->isSame($usersByCredentialsQuery->getPassword())) {
            return null;
        }
        $authUser = new AuthUser($user->getId(), $user->getRoleId(), $usersByCredentialsQuery->getLanguageId());
        $user = $user->limitToAttributes([
            UserId::class,
            Username::class,
            EmailAddress::class,
            RoleId::class,
        ]);
        return new AuthUserInformation($user, $authUser);
    }
}