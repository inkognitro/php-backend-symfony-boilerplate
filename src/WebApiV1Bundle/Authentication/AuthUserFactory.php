<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Authentication;

use App\Packages\AccessManagement\Application\Query\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;

final class AuthUserFactory
{
    private $generalAuthUserFactory;

    public function __construct(\App\Packages\AccessManagement\Application\Query\AuthUser\AuthUserFactory $generalAuthUserFactory)
    {
        $this->generalAuthUserFactory = $generalAuthUserFactory;
    }

    public function createFromRequest(Request $request): AuthUser
    {
        $languageId = $this->createLanguageIdByRequest($request);
        $jwt = (string)$request->headers->get('X-API-KEY');
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        try {
            $jwtPayload = JWT::decode($jwt, $jwtSecret, [$jwtAlgorithm]);
            return $this->createFromJWTPayload((array)$jwtPayload, $languageId);
        } catch (Exception $e) {
            return $this->generalAuthUserFactory->createGuestUser($languageId);
        }
    }

    private function createFromJWTPayload(array $jwtPayload, LanguageId $languageId): AuthUser
    {
        if (
            (isset($jwtPayload['sub']) && !is_string($jwtPayload['sub']))
            || !isset($jwtPayload['roleId']) || !is_string($jwtPayload['roleId'])
        ) {
            return $this->generalAuthUserFactory->createGuestUser($languageId);
        }
        $userId = (!empty($jwtPayload['sub']) ? UserId::fromString((string)$jwtPayload['sub']) : null);
        $roleId = RoleId::fromString($jwtPayload['roleId']);
        return new AuthUser($userId, $roleId, $languageId);
    }

    private function createLanguageIdByRequest(Request $request): LanguageId
    {
        $languageId = (string)$request->headers->get('X-API-LANG');
        $requestedLanguageId = LanguageId::fromString($languageId);
        if ($requestedLanguageId->equals(LanguageId::german())) {
            return LanguageId::german();
        }
        return LanguageId::english();
    }
}