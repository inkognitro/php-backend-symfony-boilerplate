<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Query\User;

use App\Packages\Common\Application\Query\Resource;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\ResourceAttributes\AuditLogEvent\ResourceTypeId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\CreatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UpdatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCode;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCodeSentAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerifiedAt;

final class User implements Resource
{
    private $id;
    private $emailAddress;
    private $password;
    private $username;
    private $verificationCode;
    private $verificationCodeSentAt;
    private $verifiedAt;
    private $roleId;
    private $createdAt;
    private $updatedAt;

    public static function getTypeId(): ResourceTypeId
    {
        return ResourceTypeId::user();
    }

    public function __construct(
        ?UserId $id,
        ?EmailAddress $emailAddress,
        ?Password $password,
        ?Username $username,
        ?VerificationCode $verificationCode,
        ?VerificationCodeSentAt $verificationCodeSentAt,
        ?VerifiedAt $verifiedAt,
        ?RoleId $roleId,
        ?CreatedAt $createdAt,
        ?UpdatedAt $updatedAt
    ) {
        $this->id = $id;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->username = $username;
        $this->verificationCode = $verificationCode;
        $this->verificationCodeSentAt = $verificationCodeSentAt;
        $this->verifiedAt = $verifiedAt;
        $this->roleId = $roleId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(): self
    {
        return new self(
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
    }

    public function modifyByArray(array $data): self
    {
        return new self(
            ($data[UserId::class] ?? $this->id),
            ($data[EmailAddress::class] ?? $this->emailAddress),
            ($data[Password::class] ?? $this->password),
            ($data[Username::class] ?? $this->username),
            ($data[VerificationCode::class] ?? $this->verificationCode),
            ($data[VerificationCodeSentAt::class] ?? $this->verificationCodeSentAt),
            ($data[VerifiedAt::class] ?? $this->verifiedAt),
            ($data[RoleId::class] ?? $this->roleId),
            ($data[CreatedAt::class] ?? $this->createdAt),
            ($data[UpdatedAt::class] ?? $this->updatedAt)
        );
    }

    public function getId(): ?UserId
    {
        return $this->id;
    }

    public function getRoleId(): ?RoleId
    {
        return $this->roleId;
    }

    public function getUsername(): ?Username
    {
        return $this->username;
    }

    public function getPassword(): ?Password
    {
        return $this->password;
    }

    public function getEmailAddress(): ?EmailAddress
    {
        return $this->emailAddress;
    }

    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }
}