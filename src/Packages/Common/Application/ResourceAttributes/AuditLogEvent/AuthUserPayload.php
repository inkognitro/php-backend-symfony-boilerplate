<?php declare(strict_types=1);

namespace App\Packages\Common\Application\ResourceAttributes\AuditLogEvent;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;

final class AuthUserPayload
{
    private $data;

    private function __construct(array $data)
    {
        $this->data = $data;
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

    public function toJson(): string
    {
        return json_encode($this->data);
    }
}