<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Application\Command\User;

use App\Packages\AccessManagement\Application\Query\AuthUser\Attributes\RoleId;
use App\Packages\UserManagement\Application\Query\User\Attributes\CreatedAt;
use App\Packages\UserManagement\Application\Query\User\Attributes\EmailAddress;
use App\Packages\UserManagement\Application\Query\User\Attributes\Password;
use App\Packages\UserManagement\Application\Query\User\Attributes\UpdatedAt;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerificationCode;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerificationCodeSentAt;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerifiedAt;

final class UserParams
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
        /*?UserId $id,
        ?EmailAddress $emailAddress,
        ?Password $password,
        ?Username $username,
        ?VerificationCode $verificationCode,
        ?VerificationCodeSentAt $verificationCodeSentAt,
        ?VerifiedAt $verifiedAt,
        ?RoleId $roleId,
        ?CreatedAt $createdAt,
        ?UpdatedAt $updatedAt*/
    ) {
        /*$this->id = $id;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->username = $username;
        $this->verificationCode = $verificationCode;
        $this->verificationCodeSentAt = $verificationCodeSentAt;
        $this->verifiedAt = $verifiedAt;
        $this->roleId = $roleId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;*/
    }
}