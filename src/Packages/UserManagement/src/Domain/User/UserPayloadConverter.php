<?php declare(strict_types=1);

namespace App\Packages\UserManagement\Domain\User\Event;

use App\Packages\Common\Domain\Event\Payload;
use App\Packages\UserManagement\Application\Resources\User\User;

final class UserPayloadConverter
{
    public static function convertToPayload(User $user): Payload
    {
        return Payload::fromArray([
            'id' => $user->getId()->toString(),
            'username' => $user->getUsername()->toString(),
            'emailAddress' => $user->getEmailAddress()->toString()
        ]);
    }

    public static function convertToUser(Payload $userPayload): User
    {
        $data = $userPayload->toArray();
        return new User(
            $data['id'],
            $data['username'],
            $data['emailAddress']
        );
    }
}