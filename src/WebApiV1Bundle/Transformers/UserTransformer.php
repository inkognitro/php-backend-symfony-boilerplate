<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Transformers;

use App\Packages\UserManagement\Application\Query\User\User;

final class UserTransformer
{
    public function transform(User $user): array
    {
        $data = [];
        if($user->getId()) {
            $data['id'] = $user->getId()->toString();
        }
        if($user->getRoleId()) {
            $data['roleId'] = $user->getRoleId()->toString();
        }
        if($user->getUsername()) {
            $data['username'] = $user->getUsername()->toString();
        }
        if($user->getEmailAddress()) {
            $data['emailAddress'] = $user->getEmailAddress()->toString();
        }
        return $data;
    }
}