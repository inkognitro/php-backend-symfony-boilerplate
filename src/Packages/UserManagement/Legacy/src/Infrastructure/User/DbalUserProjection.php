<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\User;

use App\Packages\Common\Domain\Event\AbstractAuditLogEvent;
use App\Packages\Common\Infrastructure\DbalConnection;
use App\Packages\Common\Infrastructure\DbalParameter;
use App\Packages\Common\Infrastructure\DbalParameters;
use App\Packages\UserManagement\Domain\User\Events\UserWasCreated;
use App\Packages\UserManagement\Domain\User\Events\VerificationCodeWasSentToUser;
use App\Packages\UserManagement\Domain\User\User;
use App\Packages\UserManagement\Domain\User\UserProjection;

final class DbalUserProjection implements UserProjection
{
    private $connection;

    public function __construct(DbalConnection $connection)
    {
        $this->connection = $connection;
    }

    public function when(AbstractAuditLogEvent $event): void
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
        //todo!
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
    private function getFieldToParameterMapping(User $user): array
    {
        $createdAtParam = ($user->getCreatedAt() !== null ?
            DbalParameter::create($user->getCreatedAt()->toDateTime(), 'datetime')
            : DbalParameter::create(null)
        );
        $verificationCode = ($user->getVerificationCodeSentAt() !== null ?
            DbalParameter::create($user->getVerificationCodeSentAt()->toString())
            : DbalParameter::create(null)
        );
        $verificationCodeSentParam = ($user->getVerificationCodeSentAt() !== null ?
            DbalParameter::create($user->getVerificationCodeSentAt()->toDateTime(), 'datetime')
            : DbalParameter::create(null)
        );
        $verifiedAtParam = ($user->getVerifiedAt() !== null ?
            DbalParameter::create($user->getVerifiedAt()->toDateTime(), 'datetime')
            : DbalParameter::create(null)
        );
        $updatedAtParam = ($user->getUpdatedAt() !== null ?
            DbalParameter::create($user->getUpdatedAt()->toDateTime(), 'datetime')
            : DbalParameter::create(null)
        );
        return [
            'id' => DbalParameter::create($user->getId()->toString()),
            'username' => DbalParameter::create($user->getUsername()->toString()),
            'email_address' => DbalParameter::create($user->getEmailAddress()->toString()),
            'password' => DbalParameter::create($user->getPassword()->toHash()),
            'role' => DbalParameter::create($user->getRoleId()->toString()),
            'verification_code' => $verificationCode,
            'verification_code_sent_at' => $verificationCodeSentParam,
            'verified_at' => $verifiedAtParam,
            'created_at' => $createdAtParam,
            'updated_at' => $updatedAtParam,
        ];
    }
}