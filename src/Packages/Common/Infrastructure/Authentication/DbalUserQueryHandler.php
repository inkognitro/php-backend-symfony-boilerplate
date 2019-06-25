<?php declare(strict_types=1);

namespace App\Packages\Common\Infrastructure\JobQueuing;

use App\Packages\Common\Application\Authentication\User;
use App\Packages\Common\Application\Authentication\UserQuery;
use App\Packages\Common\Application\Authentication\UserQueryHandler;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Resources\Role\RoleId;
use App\Resources\User\Password;
use App\Resources\User\UserId;

final class DbalUserQueryHandler implements UserQueryHandler
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(UserQuery $query): ?User
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('role_id as roleId');
        $queryBuilder->addSelect('password_hash as passwordHash');
        $queryBuilder->from('users');
        $queryBuilder->andWhere(
            $queryBuilder->expr()->eq('username', $queryBuilder->createNamedParameter($query))
        );
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        $password = Password::fromHash($row['passwordHash']);
        if (!$password->isSame(Password::fromString($query->getPassword()))) {
            return null;
        }
        $userId = UserId::fromString($row['id']);
        $roleId = RoleId::fromString($row['roleId']);
        return new User($userId, $roleId);
    }
}