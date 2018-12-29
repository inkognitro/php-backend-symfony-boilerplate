<?php declare(strict_types=1);

namespace App\Api\WebApiV1\Response;

interface Response
{
    public function getStatusCode(): int;
}