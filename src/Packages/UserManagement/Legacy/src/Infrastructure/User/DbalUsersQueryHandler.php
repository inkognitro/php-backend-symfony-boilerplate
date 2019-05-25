<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\UpdatedAt;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Application\Queries\UsersQuery;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Password;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;
use App\Packages\UserManagement\Application\Queries\UsersQueryHandler;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCodeSentAt;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerifiedAt;
use App\Packages\UserManagement\Domain\User\User;
use Doctrine\DBAL\Query\QueryBuilder;

final class DbalUsersQueryHandler implements UsersQueryHandler
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function handle(UsersQuery $query): Users //todo
    {
        $queryBuilder = $this->createQueryBuilder();
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
}