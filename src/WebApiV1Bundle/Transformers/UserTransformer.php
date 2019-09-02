<?php declare(strict_types=1);

namespace App\WebApiV1Bundle\Transformers;

use App\Packages\UserManagement\Application\Query\User\User;
use App\WebApiV1Bundle\Schema\ResponseParameter\Model;
use App\WebApiV1Bundle\Schema\ResponseParameter\ObjectResponseParameterSchema;
use App\WebApiV1Bundle\Schema\ResponseParameter\StringResponseParameterSchema;

final class UserTransformer
{
    public function transform(User $user): array
    {
        $data = [];
        if ($user->getId()) {
            $data['id'] = $user->getId()->toString();
        }
        if ($user->getRoleId()) {
            $data['roleId'] = $user->getRoleId()->toString();
        }
        if ($user->getUsername()) {
            $data['username'] = $user->getUsername()->toString();
        }
        if ($user->getEmailAddress()) {
            $data['emailAddress'] = $user->getEmailAddress()->toString();
        }
        return $data;
    }

    public static function getReferenceModel(): Model
    {
        $parameter = ObjectResponseParameterSchema::create()
            ->addProperty('id', StringResponseParameterSchema::create()->setUuidFormat(), true)
            ->addProperty('roleId', StringResponseParameterSchema::create()->setExample('user'), true)
            ->addProperty('username', StringResponseParameterSchema::create()->setExample('hubba'), true)
            ->addProperty('emailAddress', StringResponseParameterSchema::create()->setExample('hubba@example.com'), true);
        return new Model('User', 'user', $parameter);
    }
}