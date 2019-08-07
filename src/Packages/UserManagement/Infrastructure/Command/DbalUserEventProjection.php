<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Command;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\Events\VerificationCodeWasSentToUser;
use App\Packages\UserManagement\Domain\UserEventProjection;
use LogicException;

final class DbalUserEventProjection implements UserEventProjection
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

        throw new LogicException('event "' . get_class($event) . '" is not supported');
    }

    private function projectVerificationCodeWasSentToUser(VerificationCodeWasSentToUser $event): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->delete('user_email_address_verification_codes');
        $queryBuilder->andWhere("user_id = {$queryBuilder->createNamedParameter($event->getUserId()->toString())}");
        $queryBuilder->execute();

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('user_email_address_verification_codes');
        $queryBuilder->values([
            'code' => $queryBuilder->createNamedParameter($event->getVerificationCode()->toNullableString()),
            'user_id' => $queryBuilder->createNamedParameter($event->getUserId()->toString()),
            'email_address' => $queryBuilder->createNamedParameter($event->getEmailAddress()->toString()),
            'code_sent_at' => $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime'),
        ]);
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
            'password_hash' => $queryBuilder->createNamedParameter($event->getPassword()->toHash()),
            'role_id' => $queryBuilder->createNamedParameter($event->getRoleId()->toString()),
            'created_at' => $queryBuilder->createNamedParameter($event->getOccurredAt()->toDateTime(), 'datetime'),
        ]);
        $queryBuilder->execute();
    }
}