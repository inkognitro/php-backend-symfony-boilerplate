<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

final class ApiRequest
{
    private $httpFoundationRequest;
    private const AUTH_TOKEN_HEADER_KEY = 'X-API-AUTH-TOKEN';
    public const AUTH_TOKEN_PARAM_NAME = 'authToken';

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

    public function getJWT(): ApiJWT
    {
        $contentData = $this->getContentData();
        if(array_key_exists(self::AUTH_TOKEN_PARAM_NAME, $contentData)) {
            $token = $contentData[self::AUTH_TOKEN_PARAM_NAME];
            return ApiJWT::fromString($token);
        }
        return ApiJWT::fromString((string)$this->httpFoundationRequest->headers->get(self::AUTH_TOKEN_HEADER_KEY));
    }

    public function setAuthTokenHeader(string $token): void
    {
        $this->httpFoundationRequest->headers->set(self::AUTH_TOKEN_HEADER_KEY, $token);
    }

    public function getAuthUser(): AuthUser
    {
        $languageId = $this->getLanguageId();
        return $this->getJWT()->createAuthUser($languageId);
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