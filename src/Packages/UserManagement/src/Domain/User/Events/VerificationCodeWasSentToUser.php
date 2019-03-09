<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Domain\Events\AbstractEvent;
use App\Packages\Common\Domain\Events\EventId;
use App\Packages\Common\Domain\Events\OccurredAt;
use App\Packages\Common\Domain\Events\Payload;
use App\Packages\UserManagement\Domain\User\User;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCodeSentAt;

final class VerificationCodeWasSentToUser extends AbstractEvent
{
    public static function occur(VerificationCode $verificationCode, User $user, AuthUser $authUser): AbstractEvent
    {
        $occurredAt = OccurredAt::create();
        $previousUserPayload = UserPayload::fromUser($user);
        $userPayload = self::createPayload(
            UserPayload::fromUser($user, [
                VerificationCode::KEY => $verificationCode->toString(),
                VerificationCodeSentAt::KEY => $occurredAt->toString(),
            ])
        );
        return new self(EventId::create(), $occurredAt, $authUser, $userPayload, $previousUserPayload);
    }

    private static function createPayload(UserPayload $userPayload): Payload
    {
        return new Payload([
            User::KEY => $userPayload->toArray(),
        ]);
    }

    public function getUser(): User
    {
        $payload = $this->getPayload()->toArray();
        $userData = $payload[User::KEY];
        /** @var $userPayload UserPayload */
        $userPayload = new UserPayload($userData);
        return $userPayload->toUser();
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}