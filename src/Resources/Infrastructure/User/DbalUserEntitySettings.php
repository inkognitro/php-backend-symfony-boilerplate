<?php declare(strict_types=1);

namespace App\Resources\Infrastructure\User;

use App\Resources\Application\User\Attributes\CreatedAt;
use App\Resources\Application\User\Attributes\EmailAddress;
use App\Resources\Application\User\Attributes\Password;
use App\Resources\Application\User\Attributes\UserId;
use App\Resources\Application\User\Attributes\Username;
use App\Resources\Application\User\Attributes\VerificationCode;
use App\Resources\Application\User\Attributes\VerificationCodeSentAt;
use App\Resources\Application\User\Attributes\VerifiedAt;
use App\Resources\Infrastructure\DbalEntitySettings;

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