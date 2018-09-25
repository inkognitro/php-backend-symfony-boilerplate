<?php declare(strict_types=1);

namespace App\Api\ApiV1Bundle;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController
{
    protected function get(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }

    protected function post(Request $request): Response
    {
        return $this->getBadRequestResponse();
    }

    protected function getBadRequestResponse(): BadRequestResponse
    {
        return BadRequestResponse::createFromMessage('Bad request.');
    }
}