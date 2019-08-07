<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Query\User;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Query\Query;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Application\ResourceAttributes\User\CreatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UpdatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCode;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCodeSentAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerifiedAt;
use App\Packages\UserManagement\Application\Query\User\User;
use App\Packages\UserManagement\Application\Query\User\Users;
use App\Packages\UserManagement\Application\Query\User\UsersQueryHandler;
use App\Packages\Common\Infrastructure\Query\DbalQueryBuilderFactory;

final class DbalUsersQueryHandler implements UsersQueryHandler
{
    private $connection;
    private $queryBuilderFactory;
    private $userEntitySettings;

    public function __construct(
        DbalConnection $connection,
        DbalQueryBuilderFactory $queryBuilderFactory,
        DbalUserEntitySettings $userEntitySettings
    ) {
        $this->connection = $connection;
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->userEntitySettings = $userEntitySettings;
    }

    public function handle(Query $query): Users
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id as resourceId');

        foreach ($query->getAttributes() as $attribute) {
            $field = $this->userEntitySettings->getFieldByAttribute($attribute);
            $queryBuilder->addSelect($field);
        }
        $queryBuilder->from('users');
        if ($query->getCondition() !== null) {
            $this->queryBuilderFactory->addCondition($queryBuilder, $query->getCondition(), $this->userEntitySettings);
        }
        if ($query->getPagination() !== null) {
            $this->queryBuilderFactory->addPagination($queryBuilder, $query->getPagination());
        }
        $this->queryBuilderFactory->addOrderBy($queryBuilder, $query->getOrderBy(), $this->userEntitySettings);
        $rows = $queryBuilder->execute()->fetchAll();
        return $this->createUsersFromRows($rows);
    }

    private function createUsersFromRows(array $rows): Users
    {
        $users = [];
        foreach($rows as $row) {
            $users[] = $this->createUserFromRow($row);
        }
        return Users::fromUsersArray($users);
    }

    private function createUserFromRow(array $row): User
    {
        $field = $this->userEntitySettings->getFieldByAttribute(UserId::class);
        $id = (!array_key_exists($field, $row) ? null : UserId::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(EmailAddress::class);
        $emailAddress = (!array_key_exists($field, $row) ? null : EmailAddress::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(Password::class);
        $password = (!array_key_exists($field, $row) ? null : Password::fromHash($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(Username::class);
        $username = (!array_key_exists($field, $row) ? null : Username::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(VerificationCode::class);
        $verificationCode = (!array_key_exists($field, $row) ? null : VerificationCode::fromNullableString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(VerificationCodeSentAt::class);
        $verificationCodeSentAt = (!array_key_exists($field, $row) ? null : VerificationCodeSentAt::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(VerifiedAt::class);
        $verifiedAt = (!array_key_exists($field, $row) ? null : VerifiedAt::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(RoleId::class);
        $roleId = (!array_key_exists($field, $row) ? null : RoleId::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(CreatedAt::class);
        $createdAt = (!array_key_exists($field, $row) ? null : CreatedAt::fromString($row[$field]));

        $field = $this->userEntitySettings->getFieldByAttribute(UpdatedAt::class);
        $updatedAt = (!array_key_exists($field, $row) ? null : UpdatedAt::fromString($row[$field]));

        return new User(
            $id,
            $emailAddress,
            $password,
            $username,
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            $roleId,
            $createdAt,
            $updatedAt
        );
    }
}