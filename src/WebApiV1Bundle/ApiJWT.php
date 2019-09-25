<?php declare(strict_types=1);

namespace App\WebApiV1Bundle;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\LanguageId;
use App\Packages\AccessManagement\Application\ResourceAttributes\AuthUser\RoleId;
use App\Packages\Common\Application\Utilities\DateTimeFactory;
use App\Packages\UserManagement\Application\ResourceAttributes\User\UserId;
use DateTimeImmutable;
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
        $jwtPayload = $this->getPayload();
        if (!$this->isValidNotExpired($jwtPayload)) {
            return AuthUser::anonymous($languageId);
        }
        $userId = $this->findUserId();
        $roleId = $this->findRoleId();
        return new AuthUser(UserId::fromString($userId), RoleId::fromString($roleId), $languageId);
    }

    private function isValidNotExpired(array $jwtPayload): bool
    {
        if (!$this->isRequiredPayload($jwtPayload)) {
            return false;
        }
        $authTimeToLiveInMinutes = (int)getenv('APP_AUTH_JWT_TTL_IN_MINUTES');
        $dateToLive = DateTimeFactory::addMinutes($this->findCreationDate(), $authTimeToLiveInMinutes);
        $now = DateTimeFactory::create();
        return ($dateToLive > $now);
    }

    public function findUserId(): ?string
    {
        $payload = $this->getPayload();
        if(!isset($payload['sub'])) {
            return null;
        }
        return $payload['sub'];
    }

    private function findRoleId(): ?string
    {
        $payload = $this->getPayload();
        if(!isset($payload['roleId'])) {
            return null;
        }
        return $payload['roleId'];
    }

    private function isRequiredPayload(array $jwtPayload): bool
    {
        return (
            isset($jwtPayload['sub']) && is_string($jwtPayload['sub'])
            && isset($jwtPayload['roleId']) && is_string($jwtPayload['roleId'])
            && isset($jwtPayload['iat']) && is_int($jwtPayload['iat'])
        );
    }

    private function getPayload(): array
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        try {
            return (array)JWT::decode($this->jwt, $jwtSecret, [$jwtAlgorithm]);
        } catch (Exception $e) {
            return [];
        }
    }

    private function findCreationDate(): ?DateTimeImmutable
    {
        $payload = $this->getPayload();
        if (!isset($payload['iat']) || !is_int($payload['iat'])) {
            return null;
        }
        return DateTimeFactory::createFromTimestamp($payload['iat']);
    }

    public function canBeRefreshed(): bool
    {
        $creationDate = $this->findCreationDate();
        if ($creationDate === null) {
            return false;
        }
        $refreshTimeToLiveInMinutes = (int)getenv('APP_AUTH_JWT_REFRESH_TTL_IN_MINUTES');
        $dateToLive = DateTimeFactory::addMinutes($creationDate, $refreshTimeToLiveInMinutes);
        $now = DateTimeFactory::create();
        return ($dateToLive > $now);
    }

    public function toString(): string
    {
        return $this->jwt;
    }
}