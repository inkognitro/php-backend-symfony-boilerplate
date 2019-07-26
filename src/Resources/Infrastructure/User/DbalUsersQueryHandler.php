<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Packages\Common\Infrastructure\DbalConnection;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Resources\Application\User\Attributes\VerificationCodeSentAt;
use App\Resources\Application\User\Attributes\VerifiedAt;
use App\Resources\Application\User\User;
use App\Resources\Application\User\Users;
use App\Resources\Application\User\UsersQuery;
use App\Resources\Application\User\UsersQueryHandler;
use App\Resources\Infrastructure\DbalQueryBuilderFactory;

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

    public function handle(UsersQuery $query): Users
    {
        $queryBuilder = $this->connection->createQueryBuilder();
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

        return new User(
            $id,
            $emailAddress,
            $password,
            $username,
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt
        );
    }
}