<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\Events\VerificationCodeWasSentToUser;
use App\Packages\UserManagement\Domain\UserProjection;

final class DbalUserProjection implements UserProjection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AuditLogEvent $event): void
    {
        if ($event instanceof UserWasCreated) {
            $this->projectUserWasCreated($event);
        }

        if ($event instanceof VerificationCodeWasSentToUser) {
            $this->projectVerificationCodeWasSentToUser($event);
        }
    }

    private function projectVerificationCodeWasSentToUser(VerificationCodeWasSentToUser $event): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->update('users');
        $queryBuilder->set(
            'verification_code',
            $queryBuilder->createNamedParameter($event->getVerificationCode()->toString())
        );
        $queryBuilder->andWhere(
            "verification_code = {$queryBuilder->createNamedParameter($event->getUserId()->toString())}"
        );
        $queryBuilder->execute();
    }

    private function projectUserWasCreated(UserWasCreated $event): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('users');
        $queryBuilder->values([
            'id' => $queryBuilder->createNamedParameter($event->getUserId()->toString()),
            'username' => $queryBuilder->createNamedParameter($event->getUsername()->toString()),
            'email_address' => $queryBuilder->createNamedParameter($event->getEmailAddress()->toString()),
            'password' => $queryBuilder->createNamedParameter($event->getPassword()->toHash()),
            'role_id' => $queryBuilder->createNamedParameter($event->getRoleId()->toString()),
            'created_at' => $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime'),
        ]);
        $queryBuilder->execute();
    }
}