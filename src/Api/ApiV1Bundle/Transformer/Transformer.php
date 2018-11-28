<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Transformer;

use App\Resources\User\Application\User;

final class Transformer
{
    public function transform($resource): array
    {
        if($resource instanceof User) {
            return [
                'id' => $resource->getId()->toString(),
                'username' => $resource->getUsername()->toString(),
                'emailAddress' => $resource->getUsername()->toString()
            ];
        }

        throw new ResourceNotSupportedException('Resource "' . get_class($resource) . '" not supported!');
    }
}