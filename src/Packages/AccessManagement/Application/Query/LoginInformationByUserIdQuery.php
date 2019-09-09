<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;

final class LoginInformationByUserIdQuery
{
    private $userId;
    private $languageId;

    private function __construct(string $userId, LanguageId $languageId)
    {
        $this->userId = $userId;
        $this->languageId = $languageId;
    }

    public static function create(string $userId, LanguageId $languageId): self
    {
        return new self($userId, $languageId);
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getLanguageId(): LanguageId
    {
        return $this->languageId;
    }
}