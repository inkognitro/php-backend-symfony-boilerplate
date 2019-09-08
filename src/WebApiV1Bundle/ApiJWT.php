<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use Firebase\JWT\JWT;
use Exception;

final class ApiJWT
{
    private $jwt;

    private function __construct(string $jwt)
    {
        $this->jwt = $jwt;
    }

    public static function fromString(string $jwt): self
    {
        return new self($jwt);
    }

    public function createAuthUser(LanguageId $languageId): AuthUser
    {
        $jwtPayload = $this->getValidPayload();
        if (
            (isset($jwtPayload['sub']) && !is_string($jwtPayload['sub']))
            || !isset($jwtPayload['roleId']) || !is_string($jwtPayload['roleId'])
        ) {
            return AuthUser::anonymous($languageId);
        }
        $userId = (!empty($jwtPayload['sub']) ? UserId::fromString((string)$jwtPayload['sub']) : null);
        $roleId = RoleId::fromString($jwtPayload['roleId']);
        return new AuthUser($userId, $roleId, $languageId);
    }

    private function getValidPayload(): array
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        try {
            return (array)JWT::decode($this->jwt, $jwtSecret, [$jwtAlgorithm]);
        } catch (Exception $e) {
            return [];
        }
    }

    public function canBeRefreshed(): bool
    {
        $refreshTimeToLiveInMinutes = getenv('APP_AUTH_JWT_REFRESH_TTL_IN_MINUTES'); //todo check refresh token status!!
        return false;
    }
}