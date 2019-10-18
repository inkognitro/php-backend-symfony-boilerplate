<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Authentication;

use App\Packages\AccessManagement\Application\Query\AuthUser;
use App\Packages\Common\Application\Utilities\DateTimeFactory;
use App\WebApiV1Bundle\ApiJWT;
use Firebase\JWT\JWT;

final class JWTFactory
{
    private $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function createFromAuthUser(AuthUser $authUser): ApiJWT
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        $dateTimeIat = $this->dateTimeFactory::create();
        $jwtPayload = [];
        if($authUser->getUserId() !== null) {
            $jwtPayload['sub'] = $authUser->getUserId()->toString();
        }
        $jwtPayload = array_merge($jwtPayload, [
            'iat' => $dateTimeIat->getTimestamp(),
            'roleId' => $authUser->getRoleId()->toString(),
        ]);
        return ApiJWT::fromString(JWT::encode($jwtPayload, $jwtSecret, $jwtAlgorithm));
    }
}