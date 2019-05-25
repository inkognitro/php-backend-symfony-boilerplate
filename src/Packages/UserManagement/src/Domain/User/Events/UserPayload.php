<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Events;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Domain\CreatedAt;
use App\Packages\Common\Domain\UpdatedAt;
use App\Packages\Common\Domain\Event\AbstractPayload;
use App\Packages\UserManagement\Domain\User\Attributes\Values\EmailAddress;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Password;
use App\Packages\UserManagement\Domain\User\Attributes\Values\UserId;
use App\Packages\UserManagement\Domain\User\Attributes\Values\Username;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCode;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerificationCodeSentAt;
use App\Packages\UserManagement\Domain\User\Attributes\Values\VerifiedAt;
use App\Packages\UserManagement\Domain\User\User;

final class UserPayload extends AbstractPayload
{
    public static function fromUser(User $user, array $additionalPayloadData = []): self
    {
        $verificationCode = (
            $user->getVerificationCode() === null ? null : $user->getVerificationCode()->toString()
        );
        $verificationCodeSentAt = (
            $user->getVerificationCodeSentAt() === null ? null : $user->getVerificationCodeSentAt()->toString()
        );
        $verifiedAt = ($user->getVerifiedAt() === null ? null : $user->getVerifiedAt()->toString());
        $updatedAt = ($user->getUpdatedAt() === null ? null : $user->getUpdatedAt()->toString());
        $createdAt = ($user->getCreatedAt() === null ? null : $user->getCreatedAt()->toString());
        $payloadData = array_merge([
            UserId::KEY => $user->getId()->toString(),
            Username::KEY => $user->getUsername()->toString(),
            EmailAddress::KEY => $user->getEmailAddress()->toString(),
            RoleId::KEY => $user->getRoleId()->toString(),
            Password::KEY => $user->getPassword()->toHash(),
            VerificationCode::KEY => $verificationCode,
            VerificationCodeSentAt::KEY => $verificationCodeSentAt,
            VerifiedAt::KEY => $verifiedAt,
            CreatedAt::KEY => $createdAt,
            UpdatedAt::KEY => $updatedAt,
        ], $additionalPayloadData);
        return new self($payloadData);
    }

    public function toUser(): User
    {
        $payloadData = $this->data;
        $verificationCode = ($payloadData[VerificationCode::KEY] === null
            ? null : VerificationCode::fromString($payloadData[VerificationCode::KEY])
        );
        $verificationCodeSentAt = ($payloadData[VerificationCodeSentAt::KEY] === null
            ? null : VerificationCodeSentAt::fromString($payloadData[VerificationCodeSentAt::KEY])
        );
        $verifiedAt = ($payloadData[VerifiedAt::KEY] === null ? null : VerifiedAt::fromString($payloadData[VerifiedAt::KEY]));
        $createdAt = ($payloadData[CreatedAt::KEY] === null ? null : CreatedAt::fromString($payloadData[CreatedAt::KEY]));
        $updatedAt = ($payloadData[UpdatedAt::KEY] === null ? null : UpdatedAt::fromString($payloadData[UpdatedAt::KEY]));
        return new User(
            UserId::fromString($payloadData[UserId::KEY]),
            Username::fromString($payloadData[Username::KEY]),
            EmailAddress::fromString($payloadData[EmailAddress::KEY]),
            Password::fromHash($payloadData[Password::KEY]),
            RoleId::fromString($payloadData[RoleId::KEY]),
            $verificationCode,
            $verificationCodeSentAt,
            $verifiedAt,
            $createdAt,
            $updatedAt
        );
    }
}