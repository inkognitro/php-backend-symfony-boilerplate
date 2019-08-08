<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Authentication;

use App\Packages\AccessManagement\Application\Query\AuthUser\AuthUser;
use App\Packages\Common\Application\Utilities\DateTimeFactory;
use Firebase\JWT\JWT;

final class JWTFactory
{
    private $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function createFromUser(AuthUser $authUser): string
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        $jwtTimeToLiveInMinutes = (int)getenv('APP_AUTH_JWT_TTL_IN_MINUTES');
        $dateTimeIat = $this->dateTimeFactory::create();
        $dateTimeExp = $this->dateTimeFactory::addMinutes($dateTimeIat, $jwtTimeToLiveInMinutes);
        $jwtPayload = [
            'iat' => $dateTimeIat->getTimestamp(),
            'exp' => $dateTimeExp->getTimestamp(),
            'roleId' => $authUser->getRoleId()->toString(),
        ];
        if($authUser->getUserId() !== null) {
            $jwtPayload['sub'] = $authUser->getUserId()->toString();
        }
        return JWT::encode($jwtPayload, $jwtSecret, $jwtAlgorithm);
    }
}