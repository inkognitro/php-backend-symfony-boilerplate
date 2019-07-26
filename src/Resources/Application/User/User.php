<?php declare(strict_types=1);

namespace App\Resources\Application\User;

use App\Resources\Application\Resource;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Resources\Application\User\Attributes\VerificationCodeSentAt;
use App\Resources\Application\User\Attributes\VerifiedAt;

final class User implements Resource
{
    private $id;
    private $emailAddress;
    private $password;
    private $username;
    private $verificationCode;
    private $verificationCodeSentAt;
    private $verifiedAt;

    public function __construct(
        ?UserId $id,
        ?EmailAddress $emailAddress,
        ?Password $password,
        ?Username $username,
        ?VerificationCode $verificationCode,
        ?VerificationCodeSentAt $verificationCodeSentAt,
        ?VerifiedAt $verifiedAt
    ) {
        $this->id = $id;
        $this->emailAddress = $emailAddress;
        $this->password = $password;
        $this->username = $username;
        $this->verificationCode = $verificationCode;
        $this->verificationCodeSentAt = $verificationCodeSentAt;
        $this->verifiedAt = $verifiedAt;
    }
}