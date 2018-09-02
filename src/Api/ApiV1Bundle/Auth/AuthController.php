<?php declare(strict_types=1);

namespace Api\ApiV1Bundle;

use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends AbstractController
{
    protected function handleGet(Request $request): Response
    {

    }

    protected function handlePost(Request $request): Response
    {

    }
}