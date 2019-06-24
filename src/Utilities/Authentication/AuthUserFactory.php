<?php declare(strict_types=1);

namespace App\Utilities\Authentication;

use Exception;
use Firebase\JWT\JWT;

final class AuthUserFactory
{
    public function createSystemUser(): AuthUser
    {
        $userId = null;
        $role = AuthUser::SYSTEM_USER_ROLE_ID;
        $languageId = AuthUser::EN_LANGUAGE_ID;
        return new AuthUser($userId, $role, $languageId);
    }

    public function createFromJWT(string $jwt): AuthUser
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        try {
            $jwtPayload = JWT::decode($jwt, $jwtSecret, ['HS256']);
            return $this->createFromJWTPayload((array)$jwtPayload);
        } catch (Exception $e) {
            return $this->createGuestUser();
        }
    }

    private function createGuestUser(): AuthUser
    {
        $userId = null;
        $role = AuthUser::GUEST_USER_ROLE_ID;
        $languageId = AuthUser::EN_LANGUAGE_ID;
        return new AuthUser($userId, $role, $languageId);
    }

    private function createFromUserId(string $userId): AuthUser
    {
        $role = AuthUser::NORMAL_USER_ROLE_ID;
        $languageId = AuthUser::EN_LANGUAGE_ID;
        return new AuthUser($userId, $role, $languageId);
    }

    private function createFromJWTPayload(array $jwtPayload): AuthUser
    {
        $userId = (!empty($payload['sub']) ? (string)$payload['sub'] : null);
        if ($userId !== null) {
            return $this->createFromUserId($userId);
        }
        return $this->createGuestUser();
    }
}