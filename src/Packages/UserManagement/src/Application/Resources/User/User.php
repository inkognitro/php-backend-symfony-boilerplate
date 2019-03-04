<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Resources\User;

use App\Packages\AccessManagement\Application\Role\RoleId;
use App\Packages\Common\Application\Resources\AbstractResource;
use App\Packages\Common\Application\Resources\CreatedAt;
use App\Packages\Common\Application\Resources\ResourceId;
use App\Packages\Common\Application\Resources\UpdatedAt;

final class User extends AbstractResource
{
    public const KEY = 'user';

    private $id;
    private $username;
    private $emailAddress;
    private $password;
    private $roleId;
    private $verificationCode;
    private $verificationCodeSentAt;
    private $verifiedAt;
    private $createdAt;
    private $updatedAt;

    public function __construct(
        UserId $id,
        Username $username,
        EmailAddress $emailAddress,
        Password $password,
        RoleId $role,
        ?VerificationCode $verificationCode,
        ?VerificationCodeSentAt $verificationCodeSentAt,
        ?VerifiedAt $verifiedAt,
        ?CreatedAt $createdAt,
        ?UpdatedAt $updatedAt
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->roleId = $role;
        $this->verificationCode = $verificationCode;
        $this->verificationCodeSentAt = $verificationCodeSentAt;
        $this->verifiedAt = $verifiedAt;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getResourceId(): ResourceId
    {
        return $this->id;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getVerificationCode(): ?VerificationCode
    {
        return $this->verificationCode;
    }

    public function getVerificationCodeSentAt(): ?VerificationCodeSentAt
    {
        return $this->verificationCodeSentAt;
    }

    public function getVerifiedAt(): ?VerifiedAt
    {
        return $this->verifiedAt;
    }

    public function getCreatedAt(): ?CreatedAt
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?UpdatedAt
    {
        return $this->updatedAt;
    }
}