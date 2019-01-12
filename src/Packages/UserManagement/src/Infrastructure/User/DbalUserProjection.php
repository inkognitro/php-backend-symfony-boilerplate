<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Domain\Event\Projection;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\UserManagement\Application\Resources\User\Events\UserWasCreated;

final class DbalUserProjection implements Projection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function project(AbstractEvent $event): void
    {
        if ($event instanceof UserWasCreated) {
            $this->projectUserWasCreated($event);
        }
    }

    private function projectUserWasCreated(UserWasCreated $event): void
    {
        $user = $event->getUser();

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->insert('users');
        $queryBuilder->setValue(
            'id', $queryBuilder->createNamedParameter($user->getId()->toString())
        );
        $queryBuilder->setValue(
            'username', $queryBuilder->createNamedParameter($user->getUsername()->toString())
        );
        $queryBuilder->setValue(
            'email_address', $queryBuilder->createNamedParameter($user->getEmailAddress()->toString())
        );
        $queryBuilder->setValue(
            'password', $queryBuilder->createNamedParameter($user->getPassword()->toHash())
        );
        $queryBuilder->setValue(
            'role', $queryBuilder->createNamedParameter($user->getRole()->toString())
        );
        $queryBuilder->setValue(
            'created_at', $queryBuilder->createNamedParameter($user->getCreatedAt()->toDateTime(), 'datetime')
        );
        $queryBuilder->execute();
    }
}