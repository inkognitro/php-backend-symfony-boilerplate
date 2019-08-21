<?php declare(strict_types=1);

namespace App\Packages\AccessManagement\Application\Query;

use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;

final class AuthUserInformationByCredentialsQuery
{
    private $username;
    private $password;
    private $languageId;

    private function __construct(string $username, string $password, LanguageId $languageId)
    {
        $this->username = $username;
        $this->password = $password;
        $this->languageId = $languageId;
    }

    public static function fromCredentials(string $username, string $password, LanguageId $languageId): self
    {
        return new self($username, $password, $languageId);
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getLanguageId(): LanguageId
    {
        return $this->languageId;
    }
}