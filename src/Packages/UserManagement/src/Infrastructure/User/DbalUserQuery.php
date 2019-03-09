<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\UpdatedAt;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Password;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;
use App\Packages\UserManagement\Domain\User\UserQuery;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCodeSentAt;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerifiedAt;
use App\Packages\UserManagement\Domain\User\User;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalUserQuery implements UserQuery
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function findById(UserId $id): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("id = {$queryBuilder->createNamedParameter($id->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    public function findByUsername(Username $username): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("username = {$queryBuilder->createNamedParameter($username->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    public function findByEmailAddress(EmailAddress $emailAddress): ?User
    {
        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->andWhere("email_address = {$queryBuilder->createNamedParameter($emailAddress->toString())}");
        $row = $queryBuilder->execute()->fetch();
        if (!$row) {
            return null;
        }
        return $this->createUser($row);
    }

    private function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->addSelect('id');
        $queryBuilder->addSelect('username');
        $queryBuilder->addSelect('email_address as emailAddress');
        $queryBuilder->addSelect('password');
        $queryBuilder->addSelect('role');
        $queryBuilder->addSelect('verification_code as verificationCode');
        $queryBuilder->addSelect('verification_code_sent_at as verificationCodeSentAt');
        $queryBuilder->addSelect('verified_at as verifiedAt');
        $queryBuilder->addSelect('created_at as createdAt');
        $queryBuilder->addSelect('updated_at as updatedAt');
        $queryBuilder->from('users');
        return $queryBuilder;
    }

    private function createUser(array $data): User
    {
        $verificationCode = ($data['verificationCode'] === null ?
            null : VerificationCode::fromString($data['verificationCode'])
        );
        $verificationCodeSentAt = ($data['verificationCodeSentAt'] === null ?
            null : VerificationCodeSentAt::fromString($data['verificationCodeSentAt'])
        );
        $verifiedAt = ($data['verifiedAt'] === null ? null : VerifiedAt::fromString($data['verifiedAt']));
        $updatedAt = ($data['updatedAt'] === null ? null : UpdatedAt::fromString($data['updatedAt']));
        return new User(
            UserId::fromString($data['id']),
            Username::fromString($data['username']),
            EmailAddress::fromString($data['emailAddress']),
            Password::fromHash($data['password']),
            RoleId::fromString($data['role']),
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            CreatedAt::fromString($data['createdAt']),
            $updatedAt
        );
    }
}