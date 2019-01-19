<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Domain\Event\Projection;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Infrastructure\DbalParameter;
use App\Packages\Common\Infrastructure\DbalParameters;
use App\Packages\UserManagement\Application\Resources\User\Events\UserWasCreated;
use App\Packages\UserManagement\Application\Resources\User\User;

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
        $fieldToParameterMapping = $this->getFieldToParameterMapping($user);
        (new DbalParameters($fieldToParameterMapping))->applyToQueryBuilder($queryBuilder);
        foreach($fieldToParameterMapping as $field => $parameter) {
            $queryBuilder->setValue($field, ':' . $parameter->getName());
        }
        $queryBuilder->execute();
    }

    /** @return DbalParameter[] */
    public function getFieldToParameterMapping(User $user): array
    {
        return [
            'id' => DbalParameter::create($user->getId()->toString()),
            'username' => DbalParameter::create($user->getUsername()->toString()),
            'email_address' => DbalParameter::create($user->getEmailAddress()->toString()),
            'password' => DbalParameter::create($user->getPassword()->toHash()),
            'role' => DbalParameter::create($user->getRole()->toString()),
            'verification_code_sent_at' => DbalParameter::create(
                $user->getVerificationCodeSentAt()->toDateTime(), 'datetime'
            ),
            'verified_at' => DbalParameter::create($user->getVerifiedAt()->toDateTime(), 'datetime'),
            'created_at' => DbalParameter::create($user->getCreatedAt()->toDateTime(), 'datetime'),
            'updated_at' => DbalParameter::create($user->getUpdatedAt()->toDateTime(), 'datetime'),
        ];
    }
}