<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Endpoint;

use App\Api\ApiV1Bundle\Response\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Packages\Common\Application\Authorization\User as AuthUser;

interface Endpoint
{
    public function handle(Request $request, AuthUser $authUser, array $params): Response;

    public static function getSchema(): EndpointSchema;
}