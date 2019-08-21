<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

final class ApiRequest
{
    private $httpFoundationRequest;

    private function __construct(HttpFoundationRequest $httpFoundationRequest)
    {
        $this->httpFoundationRequest = $httpFoundationRequest;
    }

    public static function createFromGlobals(): self
    {
        return new self(HttpFoundationRequest::createFromGlobals());
    }

    public function getContentData(): array
    {
        $data = json_decode($this->httpFoundationRequest->getContent(), true);
        return (is_array($data) ? $data : []);
    }

    public function toHttpFoundationRequest(): HttpFoundationRequest
    {
        return $this->httpFoundationRequest;
    }

    public function getAuthUser(): AuthUser
    {
        $languageId = $this->getLanguageId();
        $jwt = (string)$this->httpFoundationRequest->headers->get('X-API-KEY');
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        try {
            $jwtPayload = JWT::decode($jwt, $jwtSecret, [$jwtAlgorithm]);
            return $this->createAuthUserFromJWTPayload((array)$jwtPayload, $languageId);
        } catch (Exception $e) {
            return AuthUser::anonymous($languageId);
        }
    }

    private function createAuthUserFromJWTPayload(array $jwtPayload, LanguageId $languageId): AuthUser
    {
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

    public function getLanguageId(): LanguageId
    {
        $languageId = (string)$this->httpFoundationRequest->headers->get('X-API-LANG');
        $requestedLanguageId = LanguageId::fromString($languageId);
        if ($requestedLanguageId->equals(LanguageId::german())) {
            return LanguageId::german();
        }
        return LanguageId::english();
    }
}