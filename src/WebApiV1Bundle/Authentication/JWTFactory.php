<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Authentication;

use App\Packages\Common\Application\Authentication\User;
use App\Utilities\DateTimeFactory;
use Firebase\JWT\JWT;

final class JWTFactory
{
    private $dateTimeFactory;

    public function __construct(DateTimeFactory $dateTimeFactory)
    {
        $this->dateTimeFactory = $dateTimeFactory;
    }

    public function createFromUser(User $user): string
    {
        $jwtSecret = getenv('APP_AUTH_JWT_SECRET');
        $jwtAlgorithm = getenv('APP_AUTH_JWT_ALGORITHM');
        $jwtTimeToLiveInMinutes = (int)getenv('APP_AUTH_JWT_TTL_IN_MINUTES');
        $dateTimeIat = $this->dateTimeFactory::create();
        $dateTimeExp = $this->dateTimeFactory::addMinutes($dateTimeIat, $jwtTimeToLiveInMinutes);
        $jwtPayload = [
            'iat' => $dateTimeIat->getTimestamp(),
            'exp' => $dateTimeExp->getTimestamp(),
            'sub' => $user->getId()->toString(),
            'roleId' => $user->getRoleId()->toString(),
        ];
        return JWT::encode($jwtPayload, $jwtSecret, $jwtAlgorithm);
    }
}