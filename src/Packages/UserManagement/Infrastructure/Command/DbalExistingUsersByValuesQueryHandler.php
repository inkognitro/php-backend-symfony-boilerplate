<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Command;

use App\Packages\Common\Application\Query\Conditions;
use App\Packages\Common\Application\Query\Like;
use App\Packages\Common\Application\Query\OrX;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\Query\User\Users;
use App\Packages\UserManagement\Application\Query\User\UsersQuery;
use App\Packages\UserManagement\Domain\UserParamsValidation\ExistingUsersByValuesQuery;
use App\Packages\UserManagement\Domain\UserParamsValidation\Users as DomainUsers;
use App\Packages\UserManagement\Domain\UserParamsValidation\User as DomainUser;
use App\Packages\UserManagement\Domain\UserParamsValidation\ExistingUsersByValuesQueryHandler;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Infrastructure\Query\User\DbalUsersQueryHandler;

final class DbalExistingUsersByValuesQueryHandler implements ExistingUsersByValuesQueryHandler
{
    private $usersQueryHandler;

    public function __construct(DbalUsersQueryHandler $usersQueryHandler)
    {
        $this->usersQueryHandler = $usersQueryHandler;
    }

    public function handle(ExistingUsersByValuesQuery $query): DomainUsers
    {
        $usersQuery = UsersQuery::create([
            UserId::class, Username::class, EmailAddress::class
        ]);
        $orX = [new Like(UserId::class, $query->getUserId()->toString())];
        if($query->getEmailAddress() !== null) {
            $orX[] = new Like(EmailAddress::class, $query->getEmailAddress()->toString());
        }
        if($query->getUsername() !== null) {
            $orX[] = new Like(Username::class, $query->getUsername()->toString());
        }
        $usersQuery = $usersQuery->andWhere(new OrX(new Conditions($orX)));
        $users = $this->usersQueryHandler->handle($usersQuery);
        return $this->createDomainUsersFromUsers($users);
    }

    private function createDomainUsersFromUsers(Users $users): DomainUsers
    {
        return array_map(function(User $user): DomainUser {
            return new DomainUser($user->getId(), $user->getUsername(), $user->getEmailAddress());
        }, $users->toArray());
    }
}