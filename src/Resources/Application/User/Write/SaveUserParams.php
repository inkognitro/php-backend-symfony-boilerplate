<?php declare(strict_types=1);

namespace App\Resources\Application\User\Write;

use App\Resources\Application\Resource;
use App\Resources\Application\Role\Attributes\RoleId;
use App\Resources\Application\User\Attributes\CreatedAt;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UpdatedAt;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Resources\Application\User\Attributes\VerificationCodeSentAt;
use App\Resources\Application\User\Attributes\VerifiedAt;

final class SaveUserParams implements Resource
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
}