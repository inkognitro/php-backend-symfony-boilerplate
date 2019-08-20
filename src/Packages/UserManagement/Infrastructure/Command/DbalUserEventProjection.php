<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Command;

use App\Packages\Common\Domain\AuditLog\AuditLogEvent;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Domain\Events\UserWasCreated;
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
            return;
        }

        throw new LogicException('event "' . get_class($event) . '" is not supported');
    }

    private function projectUserWasCreated(UserWasCreated $event): void
    {
        $user = $event->getUser();
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('users');
        $values = [
            'id' => $queryBuilder->createNamedParameter($user->getId()->toString()),
            'username' => $queryBuilder->createNamedParameter($user->getUsername()->toString()),
            'email_address' => $queryBuilder->createNamedParameter($user->getEmailAddress()->toString()),
            'password_hash' => $queryBuilder->createNamedParameter($user->getPassword()->toHash()),
            'role_id' => $queryBuilder->createNamedParameter($user->getRoleId()->toString()),
            'created_at' => $queryBuilder->createNamedParameter($user->getCreatedAt()->toDateTime(), 'datetime'),
        ];
        if($user->getVerifiedAt()->toNullableDateTime() !== null) {
            $values['verified_at'] = $queryBuilder->createNamedParameter(
                $user->getVerifiedAt()->toNullableDateTime(), 'datetime'
            );
        }
        $queryBuilder->values($values);
        $queryBuilder->execute();
    }
}