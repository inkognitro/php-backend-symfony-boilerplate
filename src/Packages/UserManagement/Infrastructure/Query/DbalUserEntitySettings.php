<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Query\User;

use App\Packages\UserManagement\Application\Query\User\Attributes\CreatedAt;
use App\Packages\UserManagement\Application\Query\User\Attributes\EmailAddress;
use App\Packages\UserManagement\Application\Query\User\Attributes\Password;
use App\Packages\UserManagement\Application\Query\User\Attributes\UserId;
use App\Packages\UserManagement\Application\Query\User\Attributes\Username;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerificationCode;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerificationCodeSentAt;
use App\Packages\UserManagement\Application\Query\User\Attributes\VerifiedAt;
use App\Packages\Common\Infrastructure\Query\DbalEntitySettings;

final class DbalUserEntitySettings implements DbalEntitySettings
{
    private const ATTRIBUTE_TO_FIELD_MAPPING = [
        UserId::class => 'id',
        Username::class => 'username',
        VerificationCode::class => 'verification_code',
        VerificationCodeSentAt::class => 'verification_code_sent_at',
        VerifiedAt::class => 'verified_at',
        CreatedAt::class => 'created_at',
        EmailAddress::class => 'email_address',
        Password::class => 'password_hash',
    ];

    public function getFieldByAttribute(string $attribute): string
    {
        return self::ATTRIBUTE_TO_FIELD_MAPPING[$attribute];
    }
}