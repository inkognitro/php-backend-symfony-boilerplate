<?php declare(strict_types=1);

namespace App\Api\WebApiV1Bundle\Response;

interface Response
{
    public function getStatusCode(): int;
}