<?php declare(strict_types=1);

namespace App\Api\WebApiV1;

use Symfony\Component\HttpFoundation\Request;

final class HttpRequestFactory
{
    public function create(): Request
    {
        return Request::createFromGlobals();
    }
}