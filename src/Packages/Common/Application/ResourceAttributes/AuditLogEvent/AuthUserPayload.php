<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Application\ResourceAttributes\PayloadAttribute;

final class AuthUserPayload extends PayloadAttribute
{
    public static function getPayloadKey(): string
    {
        return 'authUser';
    }

    public static function fromArray(array $data): self
    {
        return new self($data);
    }

    public static function fromAuthUser(AuthUser $authUser): self
    {
        return new self([
            'userId' => $authUser->getUserId()->toString(),
            'roleId' => $authUser->getRoleId()->toString(),
            'languageId' => $authUser->getLanguageId()->toString(),
        ]);
    }
}