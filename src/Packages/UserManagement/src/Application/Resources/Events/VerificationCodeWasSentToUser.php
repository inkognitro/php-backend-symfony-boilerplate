<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\Events;

use App\Packages\Common\Application\Authorization\User\User as AuthUser;
use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Resources\Events\AbstractEvent;
use App\Packages\Common\Application\Resources\Events\EventId;
use App\Packages\Common\Application\Resources\Events\OccurredAt;
use App\Packages\Common\Application\Resources\Events\Payload;
use App\Packages\UserManagement\Application\Resources\User\User;
use App\Packages\UserManagement\Application\Resources\User\VerificationCode;

final class VerificationCodeWasSentToUser extends AbstractEvent
{
    public static function occur(VerificationCode $verificationCode, User $user, AuthUser $authUser): self
    {
        $occurredAt = OccurredAt::create();
        $previousPayload = self::createPayload(UserPayload::fromUser($user), $verificationCode);
        $payload = self::createPayload(
            UserPayload::fromUser($user, ['verificationCodeSentAt' => $occurredAt->toString()]),
            $verificationCode
        );
        return new self(EventId::create(), $occurredAt, $authUser, $payload, $previousPayload);
    }

    private static function createPayload(UserPayload $userPayload, VerificationCode $verificationCode): Payload
    {
        return new Payload([
            'user' => $userPayload->toArray(),
            'verificationCode' => $verificationCode->toString()
        ]);
    }

    public function getUser(): User
    {
        $payload = $this->getPayload()->toArray();
        $userData = $payload['user'];
        /** @var $userPayload UserPayload */
        $userPayload = new UserPayload($userData);
        return $userPayload->toUser();
    }

    public function getVerificationCode(): VerificationCode
    {
        $payload = $this->getPayload()->toArray();
        return VerificationCode::fromString($payload['verificationCode']);
    }

    public function getResource(): AbstractResource
    {
        return $this->getUser();
    }

    public function mustBeLogged(): bool
    {
        return true;
    }
}