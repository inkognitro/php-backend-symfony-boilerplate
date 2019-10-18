<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Infrastructure\Query;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\CreatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\EmailAddress;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Password;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UpdatedAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\Username;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCode;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerificationCodeSentAt;
use App\Packages\UserManagement\Application\ResourceAttributes\User\VerifiedAt;
use App\Packages\Common\Infrastructure\Query\DbalEntitySettings;

final class DbalUserEntitySettings implements DbalEntitySettings
{
    private const ATTRIBUTE_TO_FIELD_MAPPING = [
        UserId::class => 'id',
        Username::class => 'username',
        VerificationCode::class => 'verification_code',
        VerificationCodeSentAt::class => 'verification_code_sent_at',
        VerifiedAt::class => 'verified_at',
        RoleId::class => 'role_id',
        CreatedAt::class => 'created_at',
        UpdatedAt::class => 'updated_at',
        EmailAddress::class => 'email_address',
        Password::class => 'password_hash',
    ];

    public function getFieldByAttribute(string $attribute): string
    {
        return self::ATTRIBUTE_TO_FIELD_MAPPING[$attribute];
    }
}