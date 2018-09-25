<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle\Auth;

use App\Api\ApiV1Bundle\AbstractController;
use App\Api\ApiV1Bundle\Response;
use Symfony\Component\HttpFoundation\Request;

final class AuthController extends AbstractController
{
    protected function get(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }

    protected function post(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }
}