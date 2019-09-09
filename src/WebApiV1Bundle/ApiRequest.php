<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

final class ApiRequest
{
    private $httpFoundationRequest;
    private const TOKEN_HEADER_KEY = 'X-API-KEY';

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
        return ApiJWT::fromString((string)$this->httpFoundationRequest->headers->get(self::TOKEN_HEADER_KEY));
    }

    public function setAuthTokenHeader(string $token): void //todo: check possible immutability
    {
        $this->httpFoundationRequest->headers->set(self::TOKEN_HEADER_KEY, $token);
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