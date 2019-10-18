<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Infrastructure\Query;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\Query\LoginInformation;
use App\Packages\AccessManagement\Application\Query\LoginInformationByUserIdQuery;
use App\Packages\AccessManagement\Application\Query\LoginInformationByUserIdQueryHandler;
use App\Packages\Common\Application\Query\Like;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;

final class DbalLoginInformationByUserIdQueryHandler implements LoginInformationByUserIdQueryHandler
{
    private $usersQueryHandler;

    public function __construct(UsersQueryHandler $usersQueryHandler)
    {
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function handle(LoginInformationByUserIdQuery $loginInformationByUserIdQuery): ?LoginInformation
    {
        $query = UsersQuery::createFromVerifiedUsers([
            UserId::class,
            Username::class,
            EmailAddress::class,
            RoleId::class,
        ]);
        $query = $query->andWhere(new Like(UserId::class, $loginInformationByUserIdQuery->getUserId()));
        $user = $this->usersQueryHandler->handle($query)->findFirst();
        if($user === null) {
            return null;
        }
        $authUser = new AuthUser($user->getId(), $user->getRoleId(), $loginInformationByUserIdQuery->getLanguageId());
        return new LoginInformation($user, $authUser);
    }
}